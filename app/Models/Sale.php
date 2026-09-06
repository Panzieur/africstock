<?php

namespace App\Models;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'reference',
        'status',
        'subtotal',
        'discount',
        'total',
        'credit_due_date',
        'notes',
        'sold_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'credit_due_date' => 'date',
        'sold_at' => 'datetime',
        'due_date' => 'date',
    ];

    /**
     * Client associé à la vente.
     * Peut être null pour une vente comptoir.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Utilisateur ayant enregistré la vente.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Produits de la vente.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Paiements associés à la vente.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Remboursements effectués pour cette vente.
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    /**
     * Montant total déjà payé.
     */
    public function getPaidAmountAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    /**
     * Montant restant à payer.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float) $this->total - $this->paid_amount);
    }

    /**
     * Statut du paiement.
     */
    public function getPaymentStatusAttribute(): string
    {
        if ($this->paid_amount <= 0) {
            return 'unpaid';
        }

        if ($this->paid_amount < (float) $this->total) {
            return 'partial';
        }

        return 'paid';
    }

    /**
     * Montant total remboursé.
     */
    public function getRefundedAmountAttribute(): float
    {
        return (float) $this->refunds()->sum('amount');
    }

    /**
     * Montant réellement encaissé après remboursements.
     */
    public function getNetPaidAmountAttribute(): float
    {
        return max(0, $this->paid_amount - $this->refunded_amount);
    }

    /**
     * Montant maximum encore remboursable.
     */
    public function getRefundableAmountAttribute(): float
    {
        return max(0, $this->paid_amount - $this->refunded_amount);
    }
}