<?php

namespace App\Jobs;

use App\Exceptions\WsMinsalException;
use App\SuspectCase;
use App\WSMinsal;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSuspectCaseMinsalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var SuspectCase
     */
    private $suspectCase;


    /**
     * SendSuspectCaseMinsalJob constructor.
     */
    public function __construct(SuspectCase $suspectCase)
    {
        $this->suspectCase = $suspectCase;
    }

    /**
     * @throws WsMinsalException
     */
    public function handle()
    {
        /* Webservice minsal */
        /* Si se crea el caso por alguien con laboratorio asignado */
        /* La muestra se crea y recepciona inmediatamente en minsal */
        if (env('ACTIVA_WS', false) != true) {
            return;
        }

        $suspectCase = $this->suspectCase;
        if($suspectCase->laboratory_id == null) {
            return;
        }

        if (!$suspectCase->laboratory->minsal_ws) {
            return;
        }

        $response = WSMinsal::crea_muestra_v2($suspectCase);
        if ($response['status'] == 0) {
            throw new WsMinsalException('Error al subir muestra a MINSAL. ' . $response['msg']);
        }
    }
}
