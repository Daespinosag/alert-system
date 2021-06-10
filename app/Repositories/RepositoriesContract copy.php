<?php

namespace App\Repositories;

use Illuminate\Container\Container;
use Illuminate\Database\Query\Builder;
use App\Repositories\RepositoryContract;

interface RepositoriesContract extends RepositoryContract
{
    /**
     * RepositoriesContract constructor.
     * @param Container $container
     */
    public function __construct(Container $container);

    /**
     * @return Builder
     */
    public function queryBuilder() : Builder;

    /**
     * @param array $columns
     * @return mixed
     */
    public function fillingColumnsModel(array $columns = []);

    /**
     * @return mixed
     */
    public function newEmptyEntity();
}