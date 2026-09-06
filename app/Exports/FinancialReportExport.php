<?php

namespace App\Exports;

use App\Models\Payment;
use App\Models\Refund;
use App\Models\Sale;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialReportExport implements FromCollection, WithHeadings, WithMapping
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
        return Payment::query()
            ->with(['sale.customer', 'sale.user'])
            ->whereHas('sale', function ($query) {
                $query->where('status', 'completed')
                    ->whereBetween('sold_at', [
                        $this->startDate . ' 00:00:00',
                        $this->endDate . ' 23:59:59',
                    ]);
            })
            ->latest('paid_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Référence vente',
            'Client',
            'Vendeur',
            'Mode de paiement',
            'Montant',
            'Référence paiement',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->paid_at?->format('d/m/Y H:i'),
            $payment->sale?->reference ?? '-',
            $payment->sale?->customer?->name ?? 'Vente comptoir',
            $payment->sale?->user?->name ?? '-',
            match ($payment->method) {
                'cash' => 'Espèces',
                'mobile_money' => 'Mobile Money',
                'bank_transfer' => 'Virement bancaire',
                'card' => 'Carte bancaire',
                default => ucfirst($payment->method),
            },
            (float) $payment->amount,
            $payment->reference ?? '-',
        ];
    }
}
