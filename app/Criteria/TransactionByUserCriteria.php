<?php

namespace App\Criteria;

use App\Models\Transaction;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class TransactionByUserCriteria implements CriteriaInterface
{
    protected $user;
    protected $orderData;

    public function __construct($user, $orderData = [])
    {
        $this->user = $user;

        $this->orderData = empty($orderData)
            ? ['date' => 'DESC', 'id'   => 'DESC']
            : $orderData;
    }

    /**
     * @param Transaction $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->byUser($this->user);

        foreach ($this->orderData as $field => $direction) {
            $model = $model->orderBy($field, $direction);
        }

        return $model;
    }
}
