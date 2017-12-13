<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'family_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @param User $user
     * @return bool
     */
    public function inFamilyWith(User $user)
    {
        if ($this->id == $user->id) {
            return true;
        }

        if (!empty($this->family_id) && $this->family_id == $user->family_id) {
            return true;
        }

        return false;
    }

    /**
     * Relation, return user transactions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Relation
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

}
