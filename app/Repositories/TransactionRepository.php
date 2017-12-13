<?php

namespace App\Repositories;

use App\Criteria\ExpensesCriteria;
use App\Criteria\TransactionByUserCriteria;
use App\Criteria\TransactionReportsCriteria;
use App\Models\User;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Auth;
use Illuminate\Http\Request;
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

    /**
     * @param string|null $from (example: '2017-11-01'])
     * @param string|null $to (example: '2017-11-30'])
     * @return array
     */
    public function getUserExpensesByPeriod($from = null, $to = null) :array
    {
        $this->pushCriteria(new TransactionByUserCriteria(Auth::user()));
        $this->pushCriteria(new ExpensesCriteria($from, $to));

        $result = [
            'total'        => 0,
            'transactions' => $this->get()
        ];

        foreach ($result['transactions'] as $transaction) {
            $result['total'] = $result['total'] + $transaction->amount;
        }

        $this->resetCriteria();

        return $result;
    }

    /**
     * @param User $user
     * @return Transaction
     */
    public function getLastTransaction($user = null)
    {
        $this->pushCriteria(new TransactionByUserCriteria(Auth::user(), ['id' => 'DESC']));

        $transaction = $this->first();

        $this->resetCriteria();

        return $transaction;
    }

    /**
     * @param Request $request
     * @param string $groupBy
     * @return array
     * @throws \Exception
     */
    public function getGroupedBy(Request $request, $groupBy) :array
    {
        $this->pushCriteria(new TransactionReportsCriteria($request, $groupBy));

        $data = $this->get()->toArray();

        $this->resetCriteria();

        return $data;
    }

}
