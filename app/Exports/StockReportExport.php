<?php

namespace App\Exports;

use App\Models\StockMovement;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected string $startDate;
    protected string $endDate;

    public function __construct(string $startDate, string $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(): Collection
    {
        return StockMovement::query()
            ->with(['product', 'user'])
            ->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59',
            ])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Produit',
            'Type',
            'Quantité',
            'Utilisateur',
            'Motif',
        ];
    }

    public function map($movement): array
    {
        return [
            $movement->created_at?->format('d/m/Y H:i'),
            $movement->product?->name ?? '-',
            match ($movement->type) {
                'entry' => 'Entrée',
                'exit' => 'Sortie',
                'adjustment' => 'Ajustement',
                default => ucfirst($movement->type),
            },
            $movement->quantity,
            $movement->user?->name ?? '-',
            $movement->reason ?? '-',
        ];
    }
}
