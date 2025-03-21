<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function account_details()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
