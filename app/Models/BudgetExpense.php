<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'finance_id',
        'description',
        'amount',
    ];

    protected $dates = [
        'date',
    ];

    /**
     * Mendapatkan anggaran terkait (jika ada)
     */
    public function finance()
    {
        return $this->belongsTo(Finance::class);
    }
}
