<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAdjustment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(ProductAdjustmentDetail::class, "adjustment_id", "id");
    }
}
