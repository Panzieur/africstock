<?php

namespace App\Http\Controllers;

use App\ActivityLogger;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query
            ->withCount('sales')
            ->with([
                'sales' => function ($query) {
                    $query->where('status', 'completed')
                        ->withSum('payments', 'amount');
                },
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

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

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
            'credit_allowed' => ['required','boolean'],
            'credit_limit' => ['required','numeric','min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if (!$validated['credit_allowed']) {
                $validated['credit_limit'] = 0;
            }

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Client créé avec succès.');
    }

    public function show(Customer $customer)
    {
        $customer->load([
            'sales' => function ($query) {
                $query->withSum('payments', 'amount')
                    ->latest('sold_at')
                    ->latest('created_at');
            },
        ]);

        $customer->loadCount('sales');

        $customer->loadSum(
            ['sales' => function ($query) {
                $query->where('status', 'completed');
            }],
            'total'
        );

        /*
        |--------------------------------------------------------------------------
        | Calcul du crédit utilisé
        |--------------------------------------------------------------------------
        */

        $customer->credit_used = $customer->sales
            ->where('status', 'completed')
            ->sum(function ($sale) {
                $paid = (float) ($sale->payments_sum_amount ?? 0);

                return max(0, (float) $sale->total - $paid);
            });

        $customer->credit_available = max(
            0,
            (float) $customer->credit_limit - $customer->credit_used
        );

        /*
        |--------------------------------------------------------------------------
        | Ventes avec reste à payer
        |--------------------------------------------------------------------------
        */

        $customer->unpaid_sales = $customer->sales
            ->where('status', 'completed')
            ->filter(function ($sale) {
                $paid = (float) ($sale->payments_sum_amount ?? 0);

                return $paid < (float) $sale->total;
            })
            ->map(function ($sale) {
                $paid = (float) ($sale->payments_sum_amount ?? 0);

                $sale->remaining_to_pay = max(
                    0,
                    (float) $sale->total - $paid
                );

                return $sale;
            });

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
            'credit_allowed' => ['required','boolean'],
            'credit_limit' => ['required','numeric','min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if (!$validated['credit_allowed']) {
                $validated['credit_limit'] = 0;
            }

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    public function deactivate(Customer $customer)
    {
        $customer->update([
            'is_active' => false,
        ]);

        ActivityLogger::log(
            'customer.deactivated',
            'Client désactivé : ' . $customer->name,
            $customer,
            [
                'customer_id' => $customer->id,
                'name' => $customer->name,
            ]
        );

        return redirect()
            ->route('customers.index')
            ->with('success', 'Client désactivé avec succès.');
    }

    public function activate(Customer $customer)
    {
        $customer->update([
            'is_active' => true,
        ]);

        ActivityLogger::log(
            'customer.activated',
            'Client activé : ' . $customer->name,
            $customer,
            [
                'customer_id' => $customer->id,
                'name' => $customer->name,
            ]
        );

        return redirect()
            ->route('customers.index')
            ->with('success', 'Client activé avec succès.');
    }
}
