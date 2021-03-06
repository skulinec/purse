<?php

namespace App\Models;

use App\Services\Contracts\DictionaryServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Transaction extends Model
{
    protected $dictionaries;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->dictionaries = app()->make(DictionaryServiceInterface::class);
    }

    protected $fillable = [
        'user_id',
        'type_dictionary_id',
        'amount',
        'date',
        'description',
    ];

    /**
     * The attributes that should be casted to native types.
     * @var array
     */
    protected $casts = [
        'id'                 => 'integer',
        'user_id'            => 'integer',
        'type_dictionary_id' => 'integer',
        'amount'             => 'float',
        'date'               => 'date',
        'description'        => 'string',
    ];

    /**
     * Validation rules
     * @var array
     */
    public static $rules = [
        'type_dictionary_id' => 'required|numeric',
        'amount'             => 'required|numeric',
        'date'               => 'required|date',
    ];

    public function scopeExpenses($query)
    {
        $transactionTable = $this->getTable();

        return $query->where("$transactionTable.amount", '<', 0);
    }

    /**
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeByUser($query, User $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        $transactionTable = $this->getTable();

        return $query->where("$transactionTable.user_id", $user->id);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeByFamily($query)
    {
        $familyId = Auth::user()->family_id;

        if (!empty($familyId)) {
            return $query->whereHas('user', function ($query) use ($familyId) {
                $query->where('family_id', $familyId);
            });
        } else {
            return $query->byUser();
        }
    }

    /**
     * Relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->HasOne(Dictionary::class, 'id', 'type_dictionary_id');
    }

    /**
     * Relation
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->dictionaries->getById($this->type_dictionary_id)->name;
    }

}
