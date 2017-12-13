<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface FamilyRepositoryInterface
{
    public function model();
    /**
     * @param integer $familyId
     * @return Collection
     */
    public function getAllMembers($familyId);
}
