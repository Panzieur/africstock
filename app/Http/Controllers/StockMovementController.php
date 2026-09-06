<?php

namespace App\Http\Controllers;

use App\ActivityLogger;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        // Recherche par produit
        if ($request->filled('search')) {
            $query->whereHas('product', function ($productQuery) use ($request) {
                $productQuery->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par utilisateur
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtre par date de début
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Filtre par date de fin
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $users = \App\Models\User::orderBy('name')->get();
        $totalStock = Product::where('is_active', true)
            ->sum('stock_quantity');

        $totalEntries = StockMovement::where('type', 'entry')
            ->sum('quantity');

        $totalExits = StockMovement::where('type', 'exit')
            ->sum('quantity');

        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->count();

        return view('stock.index', compact(
            'movements',
            'users',
            'totalStock',
            'totalEntries',
            'totalExits',
            'lowStockProducts'
        ));
    }

    public function create()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('stock.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        [$movement, $product] = DB::transaction(function () use ($validated) {

            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $stockBefore = $product->stock_quantity;
            $stockAfter = $stockBefore + $validated['quantity'];

            $product->update([
                'stock_quantity' => $stockAfter,
            ]);

            $movement = StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'entry',
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reason' => $validated['reason'] ?? null,
            ]);

            return [$movement, $product];
        });

        ActivityLogger::log(
            'stock.entry',
            'Entrée de stock : ' . $movement->quantity . ' unité(s) de ' . $product->name,
            $movement,
            [
                'product_id' => $product->id,
                'quantity' => $movement->quantity,
                'stock_before' => $movement->stock_before,
                'stock_after' => $movement->stock_after,
                'reason' => $movement->reason,
            ]
        );

        return redirect()
            ->route('stock.index')
            ->with('success', 'Entrée de stock enregistrée avec succès.');
    }

    public function createExit()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('stock.exit', compact('products'));
    }

    public function storeExit(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        [$movement, $product] = DB::transaction(function () use ($validated) {

            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $stockBefore = $product->stock_quantity;

            if ($validated['quantity'] > $stockBefore) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'quantity' => 'Stock insuffisant. Stock disponible : ' . $stockBefore,
                ]);
            }

            $stockAfter = $stockBefore - $validated['quantity'];

            $product->update([
                'stock_quantity' => $stockAfter,
            ]);

            $movement = StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'exit',
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reason' => $validated['reason'] ?? null,
            ]);

            return [$movement, $product];
        });

        ActivityLogger::log(
            'stock.exit',
            'Sortie de stock : ' . $movement->quantity . ' unité(s) de ' . $product->name,
            $movement,
            [
                'product_id' => $product->id,
                'quantity' => $movement->quantity,
                'stock_before' => $movement->stock_before,
                'stock_after' => $movement->stock_after,
                'reason' => $movement->reason,
            ]
        );

        return redirect()
            ->route('stock.index')
            ->with('success', 'Sortie de stock enregistrée avec succès.');
    }

    public function createAdjustment()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('stock.adjustment', compact('products'));
    }

    public function storeAdjustment(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        [$movement, $product] = DB::transaction(function () use ($validated) {

            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $stockBefore = $product->stock_quantity;
            $stockAfter = $validated['stock_actual'];

            $difference = $stockAfter - $stockBefore;

            if ($difference === 0) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'stock_actual' => 'Aucun ajustement nécessaire : le stock réel est identique au stock actuel.',
                ]);
            }

            $product->update([
                'stock_quantity' => $stockAfter,
            ]);

            $movement = StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'adjustment',
                'quantity' => $difference,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reason' => $validated['reason'],
            ]);

            return [$movement, $product];
        });

        ActivityLogger::log(
            'stock.adjustment',
            'Ajustement du stock : ' . $product->name,
            $movement,
            [
                'product_id' => $product->id,
                'difference' => $movement->quantity,
                'stock_before' => $movement->stock_before,
                'stock_after' => $movement->stock_after,
                'reason' => $movement->reason,
            ]
        );

        return redirect()
            ->route('stock.index')
            ->with('success', 'Ajustement de stock enregistré avec succès.');
    }
}
