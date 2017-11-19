<?php

namespace App\Services\Contracts;

use App\Models\Dictionary;

interface DictionaryServiceInterface
{
    /**
     * @return array
     */
    public function getAll() :array;

    /**
     * @param string $type
     * @return array
     */
    public function getByType($type) :array;

    /**
     * @param $id
     * @return Dictionary
     */
    public function getById($id) :Dictionary;
}