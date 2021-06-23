<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\RepositoryException;

class EloquentRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function createModel()
    {
        if (is_string($model = $this->getModel())) {
            if (! class_exists($class = '\\'.ltrim($model, '\\'))) {
                throw new RepositoryException("Class {$model} does NOT exist!");
            }

            $model = $this->getContainer()->make($class);
        }

        // Set the connection used by the model
        if (! empty($this->connection)) {
            $model = $model->setConnection($this->connection);
        }

        if (! $model instanceof Model) {
            throw new RepositoryException("Class {$model} must be an instance of \\Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }
}
