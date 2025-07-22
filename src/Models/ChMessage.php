<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'type', 'from_id', 'to_id', 'body', 'attachment', 'seen', 'created_at', 'updated_at'
    ];
}
