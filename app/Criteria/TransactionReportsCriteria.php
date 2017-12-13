<?php

namespace App\Criteria;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Prettus\Repository\Contracts\RepositoryInterface;
use Auth;
use App\Models\Dictionary;

class TransactionReportsCriteria extends AbstractCriteria
{
    const TYPE_BY_DAYS  = 'days';
    const TYPE_BY_TYPES = 'types';
    const TYPE_BY_USERS = 'users';

    protected $availableTypes = [
        self::TYPE_BY_DAYS  => self::TYPE_BY_DAYS,
        self::TYPE_BY_TYPES => self::TYPE_BY_TYPES,
        self::TYPE_BY_USERS => self::TYPE_BY_USERS
    ];

    /**
     * @param Transaction $model
     * @param RepositoryInterface $repository
     * @return Transaction
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (!isset($this->availableTypes[$this->groupBy])) {
            throw new \Exception('There is no type');
        }

        $this->setModel($model);

        if ($from = $this->request->get('from', date('Y-m-01'))) {
            $model = $model->where("{$this->table}.date", '>=', Carbon::parse($from)->format('Y-m-d'));
        }

        if ($to = $this->request->get('to', date('Y-m-d', strtotime('last day of this month')))) {
            $model = $model->where("{$this->table}.date", '<=', Carbon::parse($to)->format('Y-m-d'));
        }

        $userId = $this->request->get('user', 0);
        if ($userId == 0) {
            $model = $model->byFamily();
        } else {
            /** @var User $user */
            $user = User::find($userId);
            if ($user && Auth::user()->inFamilyWith($user)) {
                $model = $model->byUser($user);
            } else {
                throw new \Exception('Wrong user id');
            }
        }


        return $this->{$this->groupBy}($model);
    }

    /**
     * @param Transaction $model
     * @return Transaction
     */
    protected function days($model)
    {
        return $model->expenses()
            ->selectRaw('DATE_FORMAT(date, "%d.%m.%Y") as day, ABS(SUM(amount)) as sum')
            ->groupBy('date');
    }

    /**
     * @param Transaction $model
     * @return Transaction
     */
    protected function types($model)
    {
        $transactionTable = $model->getModel()->getTable();
        $dictionaryTable  = (new Dictionary())->getTable();

        return $model->expenses()
            ->selectRaw("$dictionaryTable.name as type, ABS(SUM($transactionTable.amount)) as sum")
            ->join($dictionaryTable, "$dictionaryTable.id", '=', "$transactionTable.type_dictionary_id")
            ->groupBy("$dictionaryTable.name");
    }

    /**
     * @param Transaction $model
     * @return Transaction
     */
    protected function users($model)
    {
        $transactionTable = $model->getModel()->getTable();
        $userTable        = (new User())->getTable();

        return $model->expenses()
            ->selectRaw("$userTable.name as user, ABS(SUM($transactionTable.amount)) as sum")
            ->join($userTable, "$userTable.id", '=', "$transactionTable.user_id")
            ->groupBy("$userTable.name");
    }


}
