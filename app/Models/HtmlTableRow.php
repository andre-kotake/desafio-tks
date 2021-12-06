<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HtmlTableRow extends Model
{
    public $name;
    public $amount;

    protected $fillable = [
        'name',
        'amount'
    ];
}
