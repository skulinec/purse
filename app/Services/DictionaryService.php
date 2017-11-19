<?php

namespace App\Services;

use App\Models\Dictionary;
use App\Repositories\Contracts\DictionaryRepositoryInterface;
use App\Services\Contracts\DictionaryServiceInterface;

class DictionaryService implements DictionaryServiceInterface
{
    protected $dictionaryRepository;
    protected $dictionaries       = null;
    protected $dictionariesByType = null;

    public function __construct(DictionaryRepositoryInterface $dictionaryRepository)
    {
        $this->dictionaryRepository = $dictionaryRepository;
        $this->getAll();
    }

    /**
     * @return array
     */
    public function getAll() :array
    {
        if ($this->dictionaries === null) {
            $dictionaries = $this->dictionaryRepository
                ->with('type')
                ->orderBy('dictionary_type_id')
                ->orderBy('sorting')
                ->get();

            foreach ($dictionaries as $dictionary) {
                $this->dictionaries[$dictionary->id]                 = $dictionary;
                $this->dictionariesByType[$dictionary->type->slug][] = $dictionary;
            }
        }

        return $this->dictionaries;
    }

    /**
     * @param string $type
     * @return array
     */
    public function getByType($type) :array
    {
        return $this->dictionariesByType[$type] ?? [];
    }

    /**
     * @param $id
     * @return Dictionary
     */
    public function getById($id) :Dictionary
    {
        return $this->dictionaries[$id] ?? null;
    }
}