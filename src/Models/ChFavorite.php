<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChFavorite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'favorite_id', 'created_at', 'updated_at'
    ];
}
