<?php

namespace App\Http\Controllers\API;

use App\Repositories\Administrator\AlertFloodRepository;
use App\Repositories\Administrator\AlertLandslideRepository;
use App\Repositories\Administrator\BasinRepository;
use App\Repositories\Administrator\NetRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\Administrator\StationTypeRepository;
use App\Repositories\Administrator\ZoneRepository;
use App\Repositories\AlertSystem\LandslideRepository;
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
     * @var StationTypeRepository
     */
    private $stationTypeRepository;
    /**
     * @var BasinRepository
     */
    private $basinRepository;
    /**
     * @var LandslideRepository
     */
    private $landslideRepository;
    /**
     * @var ZoneRepository
     */
    private $zoneRepository;
    /**
     * @var AlertLandslideRepository
     */
    private $alertLandslideRepository;

    /**
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param UserPermissionRepository $userPermissionRepository
     * @param AlertFloodRepository $alertFloodRepository
     * @param StationRepository $stationRepository
     * @param NetRepository $netRepository
     * @param StationTypeRepository $stationTypeRepository
     * @param BasinRepository $basinRepository
     * @param AlertLandslideRepository $alertLandslideRepository
     * @param ZoneRepository $zoneRepository
     */
    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        PermissionRepository $permissionRepository,
        UserPermissionRepository $userPermissionRepository,
        AlertFloodRepository $alertFloodRepository,
        StationRepository $stationRepository,
        NetRepository $netRepository,
        StationTypeRepository $stationTypeRepository,
        BasinRepository $basinRepository,
        AlertLandslideRepository $alertLandslideRepository,
        ZoneRepository $zoneRepository
    ){
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->userPermissionRepository = $userPermissionRepository;
        $this->alertFloodRepository = $alertFloodRepository;
        $this->stationRepository = $stationRepository;
        $this->netRepository = $netRepository;
        $this->stationTypeRepository = $stationTypeRepository;
        $this->basinRepository = $basinRepository;
        $this->zoneRepository = $zoneRepository;
        $this->alertLandslideRepository = $alertLandslideRepository;
    }

    public function landslideInformation(){
        $landslideAlerts =  $this->alertLandslideRepository->getAlerts();
        $landslideStations = $this->includeDecimalCoordinates($this->stationRepository->getStationsAlertLandslideToMap());
        $nets = $this->netRepository->getNetsById($this->extractDistinctValuesToKey('net_id',$landslideStations));
        $stationType = $this->stationTypeRepository->getStationTypeById($this->extractDistinctValuesToKey('station_type_id',$landslideStations));
        $zones = $this->zoneRepository->getZonesById($this->extractDistinctValuesToKey('zone_id',$landslideAlerts));

        return ['alerts'=> $landslideAlerts, 'stations' => $landslideStations,'nets'=>$nets,'stationType' => $stationType, 'zones' => $zones];
    }

    public function floodInformation(){
        $floodAlerts =  $this->alertFloodRepository->getAlerts();
        $floodStations = $this->includeDecimalCoordinates($this->stationRepository->getStationsAlertFloodToMap());
        $nets = $this->netRepository->getNetsById($this->extractDistinctValuesToKey('net_id',$floodStations));
        $stationType = $this->stationTypeRepository->getStationTypeById($this->extractDistinctValuesToKey('station_type_id',$floodStations));
        $basins = $this->basinRepository->getBasinsById($this->extractDistinctValuesToKey('basin_id',$floodAlerts));

        return ['alerts'=> $floodAlerts, 'stations' => $floodStations,'nets'=>$nets,'stationType' => $stationType, 'basins' => $basins];
    }

    public function userInformation(Request $request){

        $user = $this->userRepository->getUser($request->get('id'))->toArray();
        $permissions = $this->permissionRepository->getPermissions()->toArray();
        $role = $this->roleRepository->getRole($request->get('id'))->toArray();
        $userPermissions = $this->userPermissionRepository->getPermissionUser($request->get('id'))->toArray();

        return [
            'user' => $user,
            'permissions' => $permissions,
            'role' => $role,
            'userPermissions' => $userPermissions
        ];
    }

    public function getAuthUser(){
        return $this->userRepository->getUser(1)->toArray();
    }

    public function getPermissions(){
        return $this->permissionRepository->getPermissions()->toArray();
    }

    public function getRoleAuthUser(Request $request){
        return $this->roleRepository->getRole($request->get('id'))->toArray();
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
    private function calculateDecimalCoordinates($degrees, $minutes, $seconds, $direction) : float {
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

    /**
     * @param string $key
     * @param $stations
     * @return array
     */
    private function extractDistinctValuesToKey(string $key,$stations) :array {
        $elements = [];
        foreach ($stations as $station){ if (!in_array($station->{$key},$elements)){ $elements[] = $station->{$key};} }
        return $elements;
    }
}
