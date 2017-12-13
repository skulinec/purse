<?php

namespace App\Criteria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

abstract class AbstractCriteria implements CriteriaInterface
{
    /** @var Request */
    protected $request;
    protected $groupBy;

    /** @var Model */
    protected $model;

    /** @var string */
    protected $table;

    public function __construct(Request $request, $groupBy)
    {
        $this->request = $request;
        $this->groupBy = $groupBy;
    }

    /**
     * @param Model $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    abstract public function apply($model, RepositoryInterface $repository);


    /**
     * @param $model
     * @return $this
     */
    protected function setModel($model)
    {
        $this->table = $model->getModel()->getTable();
        $this->model = $model;

        return $this;
    }

    /**
     * @param string $field
     * @param string $relation
     * @param bool $strict
     * @return self
     */
    protected function condition($field, $relation = null, $strict = false)
    {
        $value = trim($this->request->get($field));
        if (strlen($value)) {
            if (empty($relation)) {
                $this->model = $strict
                    ? $this->model->where($this->table . '.' . $field, '=', $value)
                    : $this->model->where($this->table . '.' . $field, 'like', '%' . $value . '%');
            } else {
                $this->model = $this->model->whereHas($relation, function ($query) use ($field, $value, $strict) {
                    if ($strict) {
                        $query->where($field, '=', $value);
                    } else {
                        $query->where($field, 'like', '%' . $value . '%');
                    }
                });
            }
        }

        return $this;
    }
}
