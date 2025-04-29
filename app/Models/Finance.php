<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'flow_type',
        'amount',
        'date',
        'note',
    ];

    protected $dates = [
        'date',
    ];

    /**
     * Mendapatkan daftar pengeluaran yang terkait dengan anggaran ini
     */
    public function expenses()
    {
        return $this->hasMany(BudgetExpense::class);
    }


    /**
     * Cek apakah ini adalah anggaran
     */
    public function isBudget()
    {
        return $this->flow_type === 'budget';
    }
    
    /**
     * Cek apakah ini adalah pemasukan
     */
    public function isIncome()
    {
        return $this->flow_type === 'in';
    }
    
    /**
     * Mendapatkan total pengeluaran dari anggaran ini
     */
    public function getTotalExpenses()
    {
        return $this->expenses()->sum('amount');
    }
    
    /**
     * Mendapatkan sisa anggaran
     */
    public function getRemainingBudget()
    {
        if (!$this->isBudget()) {
            return 0;
        }
        
        return $this->amount - $this->getTotalExpenses();
    }
}
