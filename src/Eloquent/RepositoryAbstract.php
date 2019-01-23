<?php

namespace AwesIO\Repository\Eloquent;

use Illuminate\Database\Eloquent\Model;
use AwesIO\Repository\Contracts\CriteriaInterface;
use AwesIO\Repository\Exceptions\EntityNotDefined;
use AwesIO\Repository\Exceptions\RepositoryException;

class RepositoryAbstract implements CriteriaInterface
{
    protected $entity;

    public function __construct()
    {
        $this->entity = $this->resolveEntity();
    }

    public function withCriteria(array $criteria)
    {
        foreach ($criteria as $criterion) {
            $this->entity = $criterion->apply($this->entity);
        }
        return $this;
    }

    protected function resolveEntity()
    {
        if (!method_exists($this, 'entity')) {
            throw new EntityNotDefined();
        }

        $model = app()->make($this->entity());

        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->entity()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }
        return $model;
    }
}