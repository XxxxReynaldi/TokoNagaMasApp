<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MechanicTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'mechanic_id', 'purchase_receipt_path',
        'bank_account_name', 'bank_name', 'account_number',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }
}
