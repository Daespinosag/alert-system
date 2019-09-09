<?php


namespace App\Repositories\Administrator;

use App\Entities\Administrator\AlertLandslide;
use App\Repositories\AppBaseRepository;
use App\Repositories\RepositoriesContract;

class AlertLandslideRepository extends AppBaseRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = AlertLandslide::class;
}