<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteType extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    const PLAN = 'plan';
}
