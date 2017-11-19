<?php

namespace App\Repositories\Contracts;

use App\Models\Transaction;
use App\Models\User;

interface TransactionRepositoryInterface
{
    public function model();

    public function validate(array $data);

    public function create(array $attributes);

    public function getByUser();

    /**
     * @param string|null $from (example: '2017-11-01'])
     * @param string|null $to (example: '2017-11-30'])
     * @return array
     */
    public function getUserExpensesByPeriod($from = null, $to = null) :array;

    /**
     * @param User $user
     * @return Transaction
     */
    public function getLastTransaction($user = null) :Transaction;

}
