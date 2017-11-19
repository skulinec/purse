<?php

namespace App\Repositories;

use App\Models\Dictionary;
use App\Repositories\Contracts\DictionaryRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class DictionaryRepository extends BaseRepository implements DictionaryRepositoryInterface
{
    public function model()
    {
        return Dictionary::class;
    }
}
