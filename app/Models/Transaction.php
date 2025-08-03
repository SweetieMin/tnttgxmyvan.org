<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_date',
        'title',
        'description',
        'quantity',
        'type',
        'amount',
        'in_charge',
        'status',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0, ',', '.') . ' ₫';
    }

    public function getFormattedDateAttribute()
    {
        return $this->transaction_date ? $this->transaction_date->format('d/m/Y') : null;
    }

    public static function getCurrentBalance()
    {
        $totalIncome = self::where('type', 'income')->sum('amount');
        $totalExpense = self::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return number_format($balance, 0, ',', '.') . ' ₫';
    }
}
