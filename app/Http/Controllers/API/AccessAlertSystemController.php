<?php

namespace App\Http\Controllers\API;

use App\Repositories\Administrator\AlertFloodRepository;
use App\Repositories\Administrator\NetRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\PermissionRepository;
use App\Repositories\AlertSystem\RoleRepository;
use App\Repositories\AlertSystem\UserPermissionRepository;
use App\Repositories\AlertSystem\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccessAlertSystemController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    /**
     * @var UserPermissionRepository
     */
    private $userPermissionRepository;
    /**
     * @var AlertFloodRepository
     */
    private $alertFloodRepository;
    /**
     * @var StationRepository
     */
    private $stationRepository;
    /**
     * @var NetRepository
     */
    private $netRepository;

    /**
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param UserPermissionRepository $userPermissionRepository
     * @param AlertFloodRepository $alertFloodRepository
     * @param StationRepository $stationRepository
     * @param NetRepository $netRepository
     */
    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        PermissionRepository $permissionRepository,
        UserPermissionRepository $userPermissionRepository,
        AlertFloodRepository $alertFloodRepository,
        StationRepository $stationRepository,
        NetRepository $netRepository
    ){
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->userPermissionRepository = $userPermissionRepository;
        $this->alertFloodRepository = $alertFloodRepository;
        $this->stationRepository = $stationRepository;
        $this->netRepository = $netRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getStations(Request $request)
    {

    }

    public function getStationColor(int $temporalAlert)
    {

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getStation(Request $request)
    {

    }

    /**
     * @return mixed
     */
    public function getNets()
    {

    }


    public function getAlerts(Request $request)
    {

    }

    public function getTypeStation()
    {

    }

    public function consultAlert(Request $request)
    {

    }

    public function getStationsAlertLandslide(){
        # TODO
    }

    public function getStationsAlertFlood(){
        return [
            'floodAlerts' => $this->alertFloodRepository->getAlerts()->toArray(),
            'floodStations' => $this->includeDecimalCoordinates($this->stationRepository->getStationsAlertFloodToMap()),
        ];
    }

    public function getTrackingLandslide(){
        # TODO
    }

    public function getTrackingFlood(){
        # TODO
    }

    public function getAuthUser(){
        return $this->userRepository->getUser(1)->toArray();
    }

    public function getRoleAuthUser(Request $request){
        return $this->roleRepository->getRole($request->get('id'))->toArray();
    }

    public function getPermissions(){
        return $this->permissionRepository->getPermissions()->toArray();
    }

    public function getUserPermissions(Request $request){
        return $this->userPermissionRepository->getPermissionUser($request->get('userId'))->toArray();
    }


    /**
     * @param $degrees
     * @param $minutes
     * @param $seconds
     * @param $direction
     * @return float
     */
    private function calculateDecimalCoordinates($degrees, $minutes, $seconds, $direction)
    {
        $val = ($direction == 'W' or $direction == 'S') ? -1 : 1;

        return round(( ( $degrees + ( $minutes / 60 ) + ( $seconds / 3600 )) * $val ) , 6);
    }

    /**
     * @param $stations
     * @return mixed
     */
    private function includeDecimalCoordinates($stations){
        foreach ($stations as $station){
            $station->longitude = $this->calculateDecimalCoordinates(
                $station->longitude_degrees,
                $station->longitude_minutes,
                $station->longitude_seconds,
                $station->longitude_direction
            );
            $station->latitude = $this->calculateDecimalCoordinates(
                $station->latitude_degrees,
                $station->latitude_minutes,
                $station->latitude_seconds,
                $station->latitude_direction
            );
            unset($station->longitude_degrees,$station->longitude_minutes,$station->longitude_seconds,$station->longitude_direction);
            unset($station->latitude_degrees,$station->latitude_minutes,$station->latitude_seconds,$station->latitude_direction);
        }

        return $stations;
    }
}
