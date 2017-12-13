<?php

namespace App\Criteria;

use App\Models\Transaction;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class ExpensesCriteria implements CriteriaInterface
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    /**
     * @param Transaction $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (!$this->from || !$this->to || !strtotime($this->from) || !strtotime($this->to)) {
            $this->from = date('Y-m-01');
            $this->to   = date('Y-m-d', strtotime('last day of this month'));
        }

        return $model->expenses()
            ->where('date', '>=', $this->from)
            ->where('date', '<=', $this->to);
    }
}
