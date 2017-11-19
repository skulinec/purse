<?php

namespace App\Repositories;

use App\Repositories\Contracts\TransactionRepositoryInterface;
use Auth;
use Validator;
use App\Models\Transaction;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Validator\Exceptions\ValidatorException;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function model()
    {
        return Transaction::class;
    }

    public function validate(array $data)
    {
        return Validator::make($data, Transaction::$rules);
    }

    /**
     * Save a new entity in repository
     * @throws ValidatorException
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $attributes['user_id'] = Auth::user()->id;

        parent::create($attributes);
    }

    public function getByUser()
    {
        return $this->model
            ->byUser()
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC');
    }

    /**
     * @param string|null $from (example: '2017-11-01'])
     * @param string|null $to (example: '2017-11-30'])
     * @return array
     */
    public function getUserExpensesByPeriod($from = null, $to = null) :array
    {
        if (!$from || !$to || !strtotime($from) || !strtotime($to)) {
            $from = date('Y-m-01');
            $to   = date('Y-m-d', strtotime('last day of this month'));
        }

        $result = [
            'total'        => 0,
            'transactions' => $this->getByUser()
                ->expenses()
                ->where('date', '>=', $from)
                ->where('date', '<=', $to)
                ->get()
        ];

        foreach ($result['transactions'] as $transaction) {
            $result['total'] = $result['total'] + $transaction->amount;
        }

        return $result;
    }

    /**
     * @param User $user
     * @return Transaction
     */
    public function getLastTransaction($user = null) :Transaction
    {
        return $this->model
            ->byUser($user)
            ->orderBy('id', 'DESC')
            ->first();
    }

}
