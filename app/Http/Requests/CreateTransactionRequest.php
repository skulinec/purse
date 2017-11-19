<?php

namespace App\Http\Requests;

use App\Services\Contracts\DictionaryServiceInterface;
use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    protected $dictionaries;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null, DictionaryServiceInterface $dictionaries)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->dictionaries = $dictionaries;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    /**
     * @param array|null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $input = parent::all($keys);

        if (isset($input['type_dictionary_id'])) {
            $type = $this->dictionaries->getById($input['type_dictionary_id']);

            if ($type && in_array($type->value, ['-', '+'])) {
                $input['amount'] = (int)($type->value . abs($input['amount']));
            }
        }

        return $input;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $val = parent::get($key, $default);

        if ($key == 'amount' && $typeDictionaryId = parent::get('type_dictionary_id', null)) {
            $type = $this->dictionaries->getById($typeDictionaryId);

            if ($type && in_array($type->value, ['-', '+'])) {
                $val = (int)($type->value . abs($val));
            }
        }

        return $val;
    }
}