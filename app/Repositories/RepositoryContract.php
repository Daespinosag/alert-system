<?php

declare(strict_types=1);

namespace App\Repositories;

use Closure;
use Illuminate\Contracts\Container\Container;

interface RepositoryContract
{
    /**
     * Set the IoC container instance.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     *
     * @return static
     */
    public function setContainer(Container $container);

    /**
     * Get the IoC container instance or any of its services.
     *
     * @param string|null $service
     *
     * @return mixed
     */
    public function getContainer($service = null);

    /**
     * Set the repository model.
     *
     * @param string $model
     *
     * @return static
     */
    public function setModel($model);

    /**
     * Get the repository model.
     *
     * @return string
     */
    public function getModel(): string;

    /**
     * Set the repository identifier.
     *
     * @param string $repositoryId
     *
     * @return static
     */
    public function setRepositoryId($repositoryId);

    /**
     * Get the repository identifier.
     *
     * @return string
     */
    public function getRepositoryId(): string;

    /**
     * Set the connection associated with the repository.
     *
     * @param string $name
     *
     * @return static
     */
    public function setConnection($name);

    /**
     * Get the current connection for the repository.
     *
     * @return string
     */
    public function getConnection(): string;
    
    /**
     * Dynamically pass missing static methods to the model.
     *
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters);

    /**
     * Dynamically pass missing methods to the model.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters);
}
