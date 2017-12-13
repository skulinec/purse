<?php

namespace App\Repositories;

use App\Models\Family;
use App\Repositories\Contracts\FamilyRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class FamilyRepository extends BaseRepository implements FamilyRepositoryInterface
{
    public function model()
    {
        return Family::class;
    }

    /**
     * @param integer $familyId
     * @return Collection
     */
    public function getAllMembers($familyId)
    {
        $users = new Collection();
        if ($family = $this->findWhere(['id' => $familyId])->first()) {
            $users = $family->users;
        }

        return $users;
    }
}
