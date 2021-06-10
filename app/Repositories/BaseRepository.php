<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Contracts\Container\Container;
use App\Repositories\RepositoryContract;

abstract class BaseRepository implements RepositoryContract {
    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The connection name for the repository.
     *
     * @var string
     */
    protected $connection;

    /**
     * The repository model.
     *
     * @var string
     */
    protected $model;

    /**
     * The repository identifier.
     *
     * @var string
     */
    protected $repositoryId;


    /**
     * {@inheritdoc}
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContainer($service = null)
    {
        return is_null($service) ? ($this->container ?: app()) : ($this->container[$service] ?: app($service));
    }

    /**
     * {@inheritdoc}
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel(): string
    {
        $model = $this->getContainer('config')->get('app.repository.models');

        return $this->model ?: str_replace(['Repositories', 'Repository'], [$model, ''], static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryId($repositoryId)
    {
        $this->repositoryId = $repositoryId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepositoryId(): string
    {
        return $this->repositoryId ?: static::class;
    }
    /**
     * {@inheritdoc}
     */
    public function setConnection($name)
    {
        $this->connection = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection(): string
    {
        return $this->connection;
    }

    /**
     * {@inheritdoc}
     */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([new static(), $method], $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $parameters)
    {
        if (method_exists($model = $this->createModel(), $scope = 'scope'.ucfirst($method))) {
            $this->scope($method, $parameters);

            return $this;
        }

        return call_user_func_array([$this->createModel(), $method], $parameters);
    }
}