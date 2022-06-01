<?php

namespace App\Units\Listeners;

use App\Units\Events\MessageEvent;

class MessageReturn
{

    /**
     * Cria ouvinte de evento.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Lidar com o evento.
     *
     * @param  MessageEvent  $event
     * @return array
     */
    public function handle(MessageEvent $event)
    {
        $event = $event->event;
        if($event['statusCode'] >= 200 && $event['statusCode'] < 400){
            $event['status'] = "Sucesso";
            if($event['action'] === "Import"){
                $event['message'] = "Registro(s) adicionado a fila de importação";
            }
            if($event['action'] === "Upload"){
                $event['message'] = (isset($event['data']) ? "Arquivo(s) adicionado" : "Nenhum arquivo adicionado");
            }
            if($event['action'] === "Create"){
                is_array($event['data']) ? $event['message'] = "Registros cadastrados" : $event['message'] = "Registro cadastrado";
            }
            if($event['action'] === "Update"){
                is_array($event['data']) || (gettype($event['data']) === "integer" && $event['data'] > 1) ? $event['message'] = "Registros atualizados" : $event['message'] = "Registro atualizado";
            }
        }else{
            $event['status'] = "Erro";
        }
        return $event;
    }

}
