<?php

namespace App\Http\Controllers;

use App\ActivityLogger;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with('user')
            ->withCount([
                'items',
                'items as counted_items_count' => function ($query) {
                    $query->whereNotNull('stock_actual');
                },
            ]);

        // Recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('reference', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inventories = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('inventories.index', compact('inventories'));
    }

    public function create()
    {
        return view('inventories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $inventory = DB::transaction(function () use ($validated) {

            $lastInventory = Inventory::latest('id')->first();

            $nextNumber = $lastInventory
                ? $lastInventory->id + 1
                : 1;

            $reference = 'INV-' . now()->format('Y') . '-' . str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );

            $inventory = Inventory::create([
                'user_id' => auth()->id(),
                'reference' => $reference,
                'name' => $validated['name'],
                'status' => 'in_progress',
                'started_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            $products = Product::where('is_active', true)->get();

            foreach ($products as $product) {
                $inventory->items()->create([
                    'product_id' => $product->id,
                    'stock_theoretical' => $product->stock_quantity,
                    'stock_actual' => null,
                    'difference' => null,
                    'notes' => null,
                ]);
            }

            return $inventory;
        });

        ActivityLogger::log(
            'inventory.created',
            'Inventaire créé : ' . $inventory->reference,
            $inventory,
            [
                'name' => $inventory->name,
                'reference' => $inventory->reference,
            ]
        );

        return redirect()
            ->route('inventories.show', $inventory)
            ->with('success', 'Inventaire créé avec succès.');
    }

    public function show(Inventory $inventory)
    {
        $inventory->load([
            'user',
            'items.product',
        ]);

        $totalProducts = $inventory->items->count();

        $countedProducts = $inventory->items
            ->whereNotNull('stock_actual')
            ->count();

        $productsWithDifference = $inventory->items
            ->whereNotNull('difference')
            ->where('difference', '!=', 0)
            ->count();

        $totalDifference = $inventory->items
            ->whereNotNull('difference')
            ->sum('difference');

        return view('inventories.show', compact(
            'inventory',
            'totalProducts',
            'countedProducts',
            'productsWithDifference',
            'totalDifference'
        ));
    }

    public function updateItems(Request $request, Inventory $inventory)
    {
        if ($inventory->status !== 'in_progress') {
            return redirect()
                ->route('inventories.show', $inventory)
                ->with('error', 'Le comptage de cet inventaire ne peut plus être modifié.');
        }


        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.stock_actual' => ['required', 'integer', 'min:0'],
            'items.*.notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $inventory) {

            foreach ($validated['items'] as $itemId => $data) {

                $item = $inventory->items()
                    ->whereKey($itemId)
                    ->firstOrFail();

                $difference = $data['stock_actual'] - $item->stock_theoretical;

                $item->update([
                    'stock_actual' => $data['stock_actual'],
                    'difference' => $difference,
                    'notes' => $data['notes'] ?? null,
                ]);
            }
        });

        ActivityLogger::log(
            'inventory.counted',
            'Comptage enregistré pour l’inventaire : ' . $inventory->reference,
            $inventory,
            [
                'reference' => $inventory->reference,
                'items_count' => count($validated['items']),
            ]
        );

        return redirect()
            ->route('inventories.show', $inventory)
            ->with('success', 'Comptage de l’inventaire enregistré avec succès.');
    }

    public function complete(Inventory $inventory)
    {
        if ($inventory->status !== 'in_progress') {
            return redirect()
                ->route('inventories.show', $inventory)
                ->with('error', 'Cet inventaire ne peut plus être validé.');
        }

        $inventory->load('items.product');

        if ($inventory->items->isEmpty()) {
            return redirect()
                ->route('inventories.show', $inventory)
                ->with('error', 'Impossible de valider un inventaire sans produits.');
        }

        $uncountedItems = $inventory->items
            ->filter(fn ($item) => is_null($item->stock_actual));

        if ($uncountedItems->isNotEmpty()) {
            return redirect()
                ->route('inventories.show', $inventory)
                ->with(
                    'error',
                    'Impossible de valider l’inventaire : certains produits n’ont pas encore été comptés.'
                );
        }

        DB::transaction(function () use ($inventory) {

            foreach ($inventory->items as $item) {

                $product = Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $stockBefore = $product->stock_quantity;
                $stockAfter = $item->stock_actual;

                $difference = $stockAfter - $stockBefore;

                if ($difference !== 0) {

                    $product->update([
                        'stock_quantity' => $stockAfter,
                    ]);

                    $item->update([
                        'difference' => $difference,
                    ]);

                    $movement = $inventory->items()
                        ->whereKey($item->id)
                        ->first()
                        ->product
                        ->stockMovements()
                        ->create([
                            'user_id' => auth()->id(),
                            'type' => 'adjustment',
                            'quantity' => $difference,
                            'stock_before' => $stockBefore,
                            'stock_after' => $stockAfter,
                            'reason' => 'Ajustement suite à l’inventaire ' . $inventory->reference,
                        ]);

                        ActivityLogger::log(
                            'stock.adjustment',
                            'Ajustement de stock suite à l’inventaire ' . $inventory->reference,
                            $movement,
                            [
                                'product_id' => $item->product_id,
                                'inventory_id' => $inventory->id,
                                'inventory_reference' => $inventory->reference,
                                'difference' => $difference,
                                'stock_before' => $stockBefore,
                                'stock_after' => $stockAfter,
                            ]
                        );
                }
            }

            $inventory->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        });

        ActivityLogger::log(
            'inventory.completed',
            'Inventaire validé : ' . $inventory->reference,
            $inventory,
            [
                'reference' => $inventory->reference,
                'name' => $inventory->name,
            ]
        );

        return redirect()
            ->route('inventories.show', $inventory)
            ->with('success', 'Inventaire validé avec succès. Les écarts ont été appliqués au stock.');
    }
}
