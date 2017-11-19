<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    protected $fillable = [
        'name',
        'dictionary_type_id',
        'sorting',
    ];

    /**
     * The attributes that should be casted to native types.
     * @var array
     */
    protected $casts = [
        'id'                 => 'integer',
        'name'               => 'string',
        'dictionary_type_id' => 'integer',
        'sorting'            => 'integer',
    ];

    /**
     * Validation rules
     * @var array
     */
    public static $rules = [];

    /**
     * Relation, return dictionaries type
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne(DictionaryType::class, 'id', 'dictionary_type_id');
    }

}
