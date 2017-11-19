<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DictionaryType extends Model
{
    const TRANSACTION_TYPES = 'transaction_types';

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The attributes that should be casted to native types.
     * @var array
     */
    protected $casts = [
        'id'   => 'integer',
        'name' => 'string',
        'slug' => 'string',
    ];

    /**
     * Validation rules
     * @var array
     */
    public static $rules = [];

}
