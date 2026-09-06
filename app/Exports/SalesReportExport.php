<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping
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
        return Sale::query()
            ->with(['customer', 'user'])
            ->withSum('payments', 'amount')
            ->whereBetween('sold_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59',
            ])
            ->latest('sold_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Référence',
            'Client',
            'Vendeur',
            'Total',
            'Payé',
            'Reste à payer',
            'Statut',
        ];
    }

    public function map($sale): array
    {
        $total = (float) $sale->total;
        $paid = (float) ($sale->payments_sum_amount ?? 0);

        return [
            $sale->sold_at?->format('d/m/Y H:i'),
            $sale->reference,
            $sale->customer?->name ?? 'Client comptant',
            $sale->user?->name ?? '-',
            $total,
            $paid,
            max(0, $total - $paid),
            $sale->status === 'completed' ? 'Terminée' : 'Annulée',
        ];
    }
}
