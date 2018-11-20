<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo(InviteType::class, 'type_id');
    }
}
