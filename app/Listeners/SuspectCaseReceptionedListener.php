<?php

namespace App\Listeners;

use App\Events\SuspectCaseReceptionedEvent;
use App\Exceptions\WsMinsalException;
use App\WSMinsal;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\default_ca_bundle;

class SuspectCaseReceptionedListener implements ShouldQueue
{

    public $queue = 'default';
    public $retryAfter = 5;

    public function __construct()
    {
        //
    }

    public function handle(SuspectCaseReceptionedEvent $event)
    {
        /* Webservice minsal */
        //####### recepciona en webservice ########
        if (!env('ACTIVA_WS', false)) {
            return;
        }

        $suspectCase = $event->suspectCase;

        if ($suspectCase->laboratory_id == null || $suspectCase->laboratory->minsal_ws) {
            return;
        }

        if ($suspectCase->minsal_ws_id==null) {
            $response = WSMinsal::crea_muestra_v2($suspectCase);
            if ($response['status']==0){
                throw new WsMinsalException( $response['msg']);
            }
        }

        $response = WSMinsal::obtiene_estado_muestra($suspectCase);
        if ($response['status'] != 0) {
            throw new WsMinsalException($response['msg']);
        }

        if ($response['sample_status']!=1){
            return;
        }

        $response = WSMinsal::recepciona_muestra($suspectCase);
        if ($response['status'] == 0) {
            throw new WsMinsalException($response['msg']);
        }
    }
}
