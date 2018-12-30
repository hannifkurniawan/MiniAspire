<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
  protected $fillable = [
    'loan_id',
    'amount',
    'payment_date',
  ];
}
