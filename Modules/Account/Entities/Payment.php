<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['account_id', 'purchase_id', 'sale_id', 'amount', 'change', 'payment_method', 'payment_no', 'payment_note', 'created_by', 'updated_by'];

    public function account(){
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }
}
