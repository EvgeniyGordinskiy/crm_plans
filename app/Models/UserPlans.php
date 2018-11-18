<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPlans extends Model
{
    protected $table = 'user_plans';
    protected $guarded = [];
    public $timestamps = false;
}
