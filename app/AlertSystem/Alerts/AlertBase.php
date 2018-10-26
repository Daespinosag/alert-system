<?php

namespace App\AlertSystem\Alerts;

use App\AlertSystem\AlertSystem;
use App\Events\AlertEchoCalculatedEvent;
use App\Mail\TestEmail;
use App\Repositories\AlertSystem\UserRepository;
use Carbon\Carbon;
use function Couchbase\defaultDecoder;
use Illuminate\Support\Facades\Mail;

class AlertBase extends AlertSystem
{
    /**
     * @param $station # No quitar la station esta funcion tiene una gemela de ejecusion que necesita ese parametro
     * @param int $value
     * @return int
     */
    public function exterminateAlert($station,int $value = null)
    {
        $alert = 0;
        foreach ($this->levels as $level)
        {
            if (is_null($level->maximum)){
                if ($level->minimum <= $value){
                    $alert = $level->level;
                }

            }else{
                if ($level->minimum <= $value and $level->maximum >= $value){
                    $alert  = $level->level;
                }
            }
        }

        return $alert;
    }

    /**
     * @param array $parameters
     * @param string $alertCode
     */
    public function configurationsParameters(array $parameters = [],string $alertCode)
    {
        #se ingresan los valores entrantes por parametro y se les asigna una propiedad exitente del mismo nombre
        if (count($parameters) > 0 ){
            foreach ($parameters as $configKey => $configValue){
                if (property_exists($this, $configKey)){
                    $this->{$configKey} = $configValue;
                }else{
                    # TODO
                    dd('ERROR: no existe la propiedad '.$configKey);
                }
            }
        }

        # Consultar las estaciones que tienen registrada y activa la alerta
        $this->stations = $this->stationRepository->getForAlertSystem($alertCode, $this->stations);

        # Se configuran los espacios cincominutales a calcular en la
        $this->configureDatesToSearch(
            (is_null($this->initialDate)) ?  Carbon::now() : $this->initialDate,
            (is_null($this->finalDate)) ?  Carbon::now() : $this->finalDate
        );

        # Se calculan los niveles de alerta para la estacion.
        $this->levels = $this->alertRepository->getLevelAlert($alertCode);
    }

    /**
     * @param Carbon $date
     * @return Carbon|static
     */
    public function standardizationDate(Carbon $date)
    {
        $residue = $date->minute % 5;

        $result = ($residue == 0) ? $date : $date->addSeconds( ((5 - $residue) * 60 ));
        $result->second = 0;

        return $result;
    }

    /**
     * @param $station
     * @param $objectRepository
     * @param array $values
     * @param null $ultimateObjectRepository
     * @param string $examineFunction
     * @param string $variable
     * @return mixed
     */
    public function generateStatistics(
        $station,
        $objectRepository,
        array $values,
        $ultimateObjectRepository = null,
        string $examineFunction,
        string $variable
    )
    {
        # Se extrae el primer valor del array
        $finalValue = array_values($values)[0];
        $objectRepository->date_final = (!is_null($finalValue)) ? $finalValue->fecha.' '.$finalValue->hora : null;

        #se extrae el ultimo valor del array
        $initialValue = end($values);
        $objectRepository->date_initial = (!is_null($initialValue)) ? $initialValue->fecha.' '.$initialValue->hora : null;

        # se suma el valor de la columna entrante para calcular el valor de la alerta
        $objectRepository->{$this->code.'_value'} = array_sum(array_column($values, $variable));

        #if ($this->code == 'a25'){$objectRepository->{$this->code.'_value'} = 100 * rand(1,4); } # Todo Quitar
        #if ($this->code == 'a10'){$objectRepository->{$this->code.'_value'} = 6 * rand(1,3); } # Todo Quitar

        # se calcula el promedio de datos recuperados frente a la cantidad TOTAL
        $objectRepository->avg_recovered = round (count($values) / $this->constData * 100,2);

        # se examina el valor de la alerta para la estacion
        $objectRepository->alert = $this->{$examineFunction}($station,$objectRepository->{$this->code.'_value'} );

        #comparacion con la alerta exactamente anterior
        if (!is_null($ultimateObjectRepository)) {

            $objectRepository->num_not_change_alert = $ultimateObjectRepository->num_not_change_alert + 1;

            $objectRepository->{'dif_previous_'.$this->code} = abs(round($ultimateObjectRepository->{$this->code.'_value'}  - $objectRepository->{$this->code.'_value'} , 2));

            if (!($objectRepository->alert == $ultimateObjectRepository->alert)) {

                $objectRepository->num_not_change_alert = 0;
                $objectRepository->change_alert = true;

                if ($objectRepository->alert < $ultimateObjectRepository->alert) {
                    $objectRepository->alert_decrease = true;
                } else {
                    $objectRepository->alert_increase = true;
                }
            }
        }

        return $objectRepository;
    }

    /**
     * @param $connectionRepository
     * @param $alertRepositorySpecifies
     * @param string $functionToCalculation in App\AlertSystem\Traits\StorageServerTrait
     * @param string $eliminateFunction
     * @param string $variable
     */
    public function processAlert(
        $connectionRepository,
        $alertRepositorySpecifies,
        string $functionToCalculation,
        string $eliminateFunction,
        string  $variable
    )
    {
        foreach ($this->stations as $key => $station)
        {
            # Se consulta la conexion perteneciente a la estacion
            $connection = $connectionRepository->findOrFail($station->connection_id);

            # Se busca la tabla en la central de acopio  y se crea la coneccion
            $resultConnection = $this->searchStaticConnection($connection->name,$station->table_db_name);

            $flag = true;
            $ultimateDateTable = null;

            foreach ($this->datesRangesSearch as $dateSearch)
            {
                       # se inicializan las columnas del objeto
                $floodTable = $this->initializationObject($station,$alertRepositorySpecifies->createShowcase(),$dateSearch);

                # Se extrae el ultimo valor de la tabla a10 para una estacion especifica
                if ($flag){ $ultimateDateTable = $alertRepositorySpecifies->getUltimateDate($station->id, $dateSearch['date_execute']->format('Y-m-d H:i:s')); }

                if ($resultConnection)
                {
                    # Se consultan los datos del tipo de alerta en central de acopio (trait storagueServerTrair)
                    $result = $this->{$functionToCalculation}($resultConnection,$station->table_db_name,$dateSearch['initialDate'],$dateSearch['initialTime'],$dateSearch['finalDate'],$dateSearch['finalTime'], $this->constData);

                    if (count($result) > 0){
                        $floodTable = $this->generateStatistics($station, $floodTable, $result, $ultimateDateTable,$eliminateFunction ,$variable);
                    }else{
                       $floodTable->error = true;
                       $floodTable->comment = ' | no datos en adquisicion | ';
                    }
                }else{
                    $floodTable->error = true;
                    $floodTable->comment = ' | no fue posible encontrar estacion en adquisicion | ';
                }

                $ultimateDateTable = $floodTable;
                $flag = false;

                # se ingresa el valor calculado al array global de valores
               array_push($this->values, $floodTable->toArray());
            }
            #if ($key == 15){dd($this);}
        }
    }

    /**
     * @param $repository
     */
    public function createInAlertSpecificTable($repository)
    {
        foreach ($this->values as $value){ $repository->create($value); }
    }

    /**
     * @param $station
     * @param $objectRepository
     * @param $dateSearch
     * @return mixed
     */
    public function initializationObject($station, $objectRepository, array $dateSearch)
    {
        $objectRepository->station = $station->id;
        $objectRepository->name_station = $station->name;
        $objectRepository->date_execution = $dateSearch['date_execute']->format('Y-m-d H:i:s');
        $objectRepository->{$this->code.'_value'} = null;
        $objectRepository->{'dif_previous_'.$this->code} = null;
        $objectRepository->num_not_change_alert = null;
        $objectRepository->change_alert = false;
        $objectRepository->alert_decrease = false;
        $objectRepository->alert_increase = false;
        $objectRepository->error = false;
        $objectRepository->comment = null;
        $objectRepository->alert = -1;

        return $objectRepository;
    }

    public function getAlertsDefences()
    {
        $arr = ['red'=>[],'orange'=>[],'yellow'=>[],'green'=>[],'changes' => false];

        foreach ($this->values as $data){
            if ($data['change_alert']){

                $arr['changes'] = true;
                $data['alert_status'] =  ($data['alert_decrease']) ? '/images/alert-icons/alert-decrease.png' : '/images/alert-icons/alert-increase.png';

                switch ($data['alert']) {
                    case 0:
                        array_push($arr['green'],(object)$data);
                        break;
                    case 1:
                        array_push($arr['yellow'],(object)$data);
                        break;
                    case 2:
                        array_push($arr['orange'],(object)$data);
                        break;
                    case 3:
                        array_push($arr['red'],(object)$data);
                        break;
                }
            }
        }

        $this->valuesChangeAlert = (object)$arr;

        return $this->valuesChangeAlert;
    }

    /**
     * @return array
     */
    public function formatDataToEvent() : array
    {
        $arr = [];

        foreach ($this->values as $value){
            $temporalArr = [];
            $temporalArr['alert'] = $this->code;
            $temporalArr['station'] = $value['station'];
            $temporalArr['change_alert'] = $value['change_alert'];
            $temporalArr['values'][$this->code.'_value'] = $value[$this->code.'_value'];
            $temporalArr['values']['alert'] = $value['alert'];
            $temporalArr['values']['date_execution'] = $value['date_execution'];
            $temporalArr['values']['error'] = $value['error'];
            $temporalArr['values']['comment'] = $value['comment'];

            array_push($arr,$temporalArr);
        }

        return $arr;
    }

    /**
     * @param UserRepository $repository
     * @param string $code
     * @param string $name
     * @param string $message
     */
    public function sendChangesEmail(UserRepository $repository, string $code, string $name, string $message)
    {
        $arrEmail = [];

        # se extraen los datos que cambiaron de alerta
        $data = $this->getAlertsDefences();

        # se extraen los emails conpermisos para recibir la alerta
        $emails = $repository->getEmailUserFromAlert($code);

        # se cambia el formato de los arrays extraidos
        foreach ($emails as $email){ array_push($arrEmail,$email->email);}

        # se pregunta si existen cambios y si existen correos para poder enviar el email
        if ($data->changes and count($arrEmail) > 0){
            Mail::to('ideaalertas@gmail.com')->bcc($arrEmail)->send(new TestEmail($name, $data, $message, $code));
        }
    }

    /**
     *
     */
    public function sendEventDataAB()
    {
        $data = $this->formatDataToEvent();
        event(new AlertEchoCalculatedEvent($data));
    }

}