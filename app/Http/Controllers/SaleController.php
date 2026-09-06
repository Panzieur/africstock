<?php

namespace App\Http\Controllers;

use App\ActivityLogger;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'user'])
            ->withCount('items')
            ->latest()
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load([
            'customer',
            'user',
            'items.product',
            'payments',
            'refunds.user',
        ]);

        return view('sales.show', compact('sale'));
    }

    public function cancel(Sale $sale)
    {
        if ($sale->status === 'cancelled') {
            return redirect()
                ->route('sales.show', $sale)
                ->with('error', 'Cette vente est déjà annulée.');
        }

        $paidAmount = (float) $sale->payments()->sum('amount');
        $refundedAmount = (float) $sale->refunds()->sum('amount');

        if ($paidAmount > 0) {

            if ($refundedAmount < $paidAmount) {
                $remainingToRefund = $paidAmount - $refundedAmount;

                return redirect()
                    ->route('sales.show', $sale)
                    ->with(
                        'error',
                        'Impossible d’annuler cette vente. Il reste ' .
                        number_format($remainingToRefund, 0, ',', ' ') .
                        ' FCFA à rembourser avant l’annulation.'
                    );
            }
        }

        DB::transaction(function () use ($sale) {

            $sale->load('items.product');

            foreach ($sale->items as $item) {

                $product = \App\Models\Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $stockBefore = $product->stock_quantity;
                $stockAfter = $stockBefore + $item->quantity;

                $product->update([
                    'stock_quantity' => $stockAfter,
                ]);

                $movement = $product->stockMovements()->create([
                    'user_id' => auth()->id(),
                    'type' => 'entry',
                    'quantity' => $item->quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reason' => 'Annulation de la vente ' . $sale->reference,
                ]);

                ActivityLogger::log(
                    'stock.entry',
                    'Entrée de stock suite à l’annulation de la vente ' . $sale->reference . ' : ' . $product->name,
                    $movement,
                    [
                        'product_id' => $product->id,
                        'sale_id' => $sale->id,
                        'sale_reference' => $sale->reference,
                        'quantity' => $item->quantity,
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                    ]
                );
            }

            $sale->update([
                'status' => 'cancelled',
            ]);
        });

        ActivityLogger::log(
            'sale.cancelled',
            'Vente annulée : ' . $sale->reference,
            $sale,
            [
                'total' => $sale->total,
            ]
        );

        return redirect()
            ->route('sales.show', $sale)
            ->with(
                'success',
                'Vente annulée avec succès. Le stock a été rétabli.'
            );
    }

    public function create()
    {
        $customers = \App\Models\Customer::where('is_active', true)
            ->with([
                'sales' => function ($query) {
                    $query->where('status', 'completed')
                        ->withSum('payments', 'amount');
                },
            ])
            ->orderBy('name')
            ->get();

        foreach ($customers as $customer) {

            $customer->credit_used = $customer->sales->sum(function ($sale) {
                $paid = (float) ($sale->payments_sum_amount ?? 0);

                return max(0, (float) $sale->total - $paid);
            });

            $customer->credit_available = max(
                0,
                (float) $customer->credit_limit - $customer->credit_used
            );
        }

        $products = \App\Models\Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => [
                'nullable',
                'exists:customers,id',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'payment_method' => [
                'nullable',
                'in:cash,mobile_money,bank_transfer,card',
            ],

            'payment_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $sale = DB::transaction(function () use ($validated) {

            $subtotal = 0;

            // Créer la vente
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => auth()->id(),
                'reference' => 'VNT-' . now()->format('YmdHis') . '-' . rand(100, 999),
                'status' => 'completed',
                'subtotal' => 0,
                'discount' => 0,
                'total' => 0,
                'notes' => $validated['notes'] ?? null,
                'sold_at' => now(),
            ]);

            foreach ($validated['items'] as $item) {

                $product = \App\Models\Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // Vérifier le stock
                if ($item['quantity'] > $product->stock_quantity) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'items' => "Stock insuffisant pour le produit : {$product->name}. Stock disponible : {$product->stock_quantity}.",
                    ]);
                }

                $unitPrice = $product->selling_price;
                $lineSubtotal = $unitPrice * $item['quantity'];

                $subtotal += $lineSubtotal;

                // Créer la ligne de vente
                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $lineSubtotal,
                ]);

                // Stock avant
                $stockBefore = $product->stock_quantity;

                // Nouveau stock
                $stockAfter = $stockBefore - $item['quantity'];

                $product->update([
                    'stock_quantity' => $stockAfter,
                ]);

                $movement = $product->stockMovements()->create([
                    'user_id' => auth()->id(),
                    'type' => 'exit',
                    'quantity' => $item['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reason' => 'Vente ' . $sale->reference,
                ]);

                ActivityLogger::log(
                    'stock.exit',
                    'Sortie de stock suite à la vente ' . $sale->reference . ' : ' . $product->name,
                    $movement,
                    [
                        'product_id' => $product->id,
                        'sale_id' => $sale->id,
                        'sale_reference' => $sale->reference,
                        'quantity' => $item['quantity'],
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                    ]
                );
            }

            // Pour l'instant, pas de remise
            $discount = 0;

            $total = $subtotal - $discount;

            $paymentAmount = $validated['payment_amount'] ?? 0;
            $paymentAmount = (float) $paymentAmount;

            // Empêcher un paiement supérieur au total
            if ($paymentAmount > $total) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment_amount' => 'Le montant payé ne peut pas être supérieur au total de la vente.',
                ]);
            }

            $creditAmount = $total - $paymentAmount;

            $settings = Setting::first();

            /*
            |--------------------------------------------------------------------------
            | Date d'échéance du crédit
            |--------------------------------------------------------------------------
            */

            $creditDueDate = null;

            if ($creditAmount > 0) {
                $creditDueDate = now()
                    ->addDays($settings->credit_due_days)
                    ->toDateString();
            }

            /*
            |--------------------------------------------------------------------------
            | Vérification des paramètres de vente
            |--------------------------------------------------------------------------
            */

            // Paiement partiel
            if (
                $paymentAmount > 0 &&
                $paymentAmount < $total &&
                !$settings->allow_partial_payments
            ) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment_amount' => 'Les paiements partiels sont actuellement désactivés dans les paramètres.',
                ]);
            }

            // Vente à crédit
            if ($creditAmount > 0 && !$settings->allow_credit_sales) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment_amount' => 'Les ventes à crédit sont actuellement désactivées dans les paramètres.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Vérification du crédit client
            |--------------------------------------------------------------------------
            */

            if ($creditAmount > 0) {

                // Un client est obligatoire pour une vente à crédit ou partiellement payée.
                if (empty($validated['customer_id'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'customer_id' => 'Un client est obligatoire pour une vente à crédit ou partiellement payée.',
                    ]);
                }

                $customer = \App\Models\Customer::findOrFail(
                    $validated['customer_id']
                );

                // Le client doit être autorisé à prendre du crédit.
                if (!$customer->credit_allowed) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'customer_id' => 'Ce client n’est pas autorisé à effectuer des achats à crédit.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Calcul du crédit déjà utilisé
                |--------------------------------------------------------------------------
                */

                $currentCredit = (float) $customer->sales()
                    ->where('status', 'completed')
                    ->withSum('payments', 'amount')
                    ->get()
                    ->sum(function ($sale) {
                        return max(
                            0,
                            (float) $sale->total -
                            (float) ($sale->payments_sum_amount ?? 0)
                        );
                    });

                $newCredit = $creditAmount;

                $availableCredit =
                    (float) $customer->credit_limit - $currentCredit;

                if ($newCredit > $availableCredit) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'payment_amount' =>
                            'La limite de crédit du client est dépassée. ' .
                            'Crédit disponible : ' .
                            number_format($availableCredit, 0, ',', ' ') .
                            ' FCFA.',
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Mise à jour finale de la vente
            |--------------------------------------------------------------------------
            */

            $sale->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'credit_due_date' => $creditDueDate,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Enregistrer le paiement
            |--------------------------------------------------------------------------
            */

            if ($paymentAmount > 0) {

                // Un montant payé doit obligatoirement avoir un mode de paiement
                if (empty($validated['payment_method'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'payment_method' => 'Veuillez sélectionner un mode de paiement.',
                    ]);
                }

                $sale->payments()->create([
                    'method' => $validated['payment_method'],
                    'amount' => $paymentAmount,
                    'reference' => $validated['payment_reference'] ?? null,
                    'notes' => $validated['payment_notes'] ?? null,
                    'paid_at' => now(),
                ]);

                ActivityLogger::log(
                    'payment.created',
                    'Paiement enregistré pour la vente ' . $sale->reference,
                    $sale,
                    [
                        'amount' => $paymentAmount,
                        'method' => $validated['payment_method'],
                        'reference' => $validated['payment_reference'] ?? null,
                    ]
                );
            }

            return $sale;
        });

        ActivityLogger::log(
            'sale.created',
            'Vente créée : ' . $sale->reference,
            $sale,
            [
                'total' => $sale->total,
                'customer_id' => $sale->customer_id,
            ]
        );

        return redirect()
            ->route('sales.index')
            ->with('success', 'Vente enregistrée avec succès.');
    }

    public function storePayment(Request $request, Sale $sale)
    {
        if ($sale->status === 'cancelled') {
            return redirect()
                ->route('sales.show', $sale)
                ->with('error', 'Impossible d’ajouter un paiement à une vente annulée.');
        }

        $validated = $request->validate([
            'payment_method' => [
                'required',
                'in:cash,mobile_money,bank_transfer,card',
            ],

            'payment_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $paidAmount = (float) $sale->payments()->sum('amount');
        $remainingAmount = (float) $sale->total - $paidAmount;
        $paymentAmount = (float) $validated['payment_amount'];

        if ($paymentAmount > $remainingAmount) {
            return redirect()
                ->route('sales.show', $sale)
                ->with(
                    'error',
                    'Le montant du paiement ne peut pas être supérieur au reste à payer.'
                );
        }

        $sale->payments()->create([
            'method' => $validated['payment_method'],
            'amount' => $paymentAmount,
            'reference' => $validated['payment_reference'] ?? null,
            'notes' => $validated['payment_notes'] ?? null,
            'paid_at' => now(),
        ]);

        ActivityLogger::log(
            'payment.created',
            'Paiement enregistré pour la vente ' . $sale->reference,
            $sale,
            [
                'amount' => $paymentAmount,
                'method' => $validated['payment_method'],
                'reference' => $validated['payment_reference'] ?? null,
            ]
        );

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'Paiement enregistré avec succès.');
    }

    public function storeRefund(Request $request, Sale $sale)
    {
        if ($sale->status === 'cancelled') {
            return redirect()
                ->route('sales.show', $sale)
                ->with(
                    'error',
                    'Impossible de rembourser une vente déjà annulée.'
                );
        }

        $paidAmount = (float) $sale->payments()->sum('amount');
        $refundedAmount = (float) $sale->refunds()->sum('amount');

        $refundableAmount = max(
            0,
            $paidAmount - $refundedAmount
        );

        if ($refundableAmount <= 0) {
            return redirect()
                ->route('sales.show', $sale)
                ->with(
                    'error',
                    'Aucun montant n’est disponible pour un remboursement.'
                );
        }

        $validated = $request->validate([
            'refund_amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . $refundableAmount,
            ],

            'refund_method' => [
                'required',
                'in:cash,mobile_money,bank_transfer,card',
            ],

            'refund_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'refund_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use ($validated, $sale) {

            $sale->refresh();

            $paidAmount = (float) $sale->payments()->sum('amount');
            $refundedAmount = (float) $sale->refunds()->sum('amount');

            $refundableAmount = max(
                0,
                $paidAmount - $refundedAmount
            );

            $refundAmount = (float) $validated['refund_amount'];

            if ($refundAmount > $refundableAmount) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'refund_amount' => 'Le montant du remboursement dépasse le montant encore remboursable.',
                ]);
            }

            $sale->refunds()->create([
                'user_id' => auth()->id(),
                'amount' => $refundAmount,
                'method' => $validated['refund_method'],
                'reference' => $validated['refund_reference'] ?? null,
                'reason' => $validated['refund_reason'],
                'refunded_at' => now(),
            ]);

            ActivityLogger::log(
                'refund.created',
                'Remboursement enregistré pour la vente ' . $sale->reference,
                $sale,
                [
                    'amount' => $refundAmount,
                    'method' => $validated['refund_method'],
                    'reference' => $validated['refund_reference'] ?? null,
                    'reason' => $validated['refund_reason'],
                ]
            );
        });

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'Remboursement enregistré avec succès.');
    }
}