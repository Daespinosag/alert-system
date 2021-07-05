<?php

namespace App\Helpers;

use App\Repositories\AlertSystem\LogsRepository;
use Carbon\Carbon;

class Log
{
    public static function newError($phpClass, $priority, $comments, $exeptionMessage, $parameterIn = [], $sendEmail = false)
    {
        Log::newLog('Error', $phpClass, $priority, $comments, $exeptionMessage, $parameterIn, $sendEmail);
    }

    public static function newFail($phpClass, $priority, $comments, $exeptionMessage, $parameterIn = [], $sendEmail = false)
    {
        Log::newLog('Fallo', $phpClass, $priority, $comments, $exeptionMessage, $parameterIn, $sendEmail);

    }

    private static function newLog($status, $phpClass, $priority, $comments, $exeptionMessage, $parameterIn = [], $sendEmail = false)
    {
        $logRepository = new  LogsRepository();
        $log = $logRepository->newObject();
        $log->code = $phpClass;
        $log->type = $status;
        $log->status = 'Active';
        $log->priority = $priority;
        $log->date = Carbon::now();
        $log->comments = $comments;
        $log->aditionalData = json_encode([
            'exeptionMessage' => $exeptionMessage,
            'parametersIn' => json_encode($parameterIn)
        ]);
        $log->save();
        if ($sendEmail == true) {
            $logRepository->sendEmail($log);
        }
    }
}