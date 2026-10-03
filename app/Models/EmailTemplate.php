<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'crm_emailtemplate';

    protected $guarded = [];

    public $timestamps = false;
}
