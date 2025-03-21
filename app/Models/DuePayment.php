<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuePayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    // due payment details
    public function details()
    {
        return $this->belongsTo(DuePaymentDetail::class, 'due_payment_id', 'id');
    }
}
