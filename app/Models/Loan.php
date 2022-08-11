<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'loan_amount', 'loan_term','status','approval_status','modified_user_id',
    ];

    public function loanRepayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function getTotalPaidAmount()
    {
        return $this->hasMany(LoanRepayment::class)->where('status', '2')->sum('amount');
    }
}
