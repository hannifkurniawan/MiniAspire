<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
  protected $fillable = [
      'user_id',
      'amount',
      'duration',
      'repayment_frequency',
      'interest_rate',
      'arrangement_fee',
      'fix_installment',
      'is_settled',
  ];
}
