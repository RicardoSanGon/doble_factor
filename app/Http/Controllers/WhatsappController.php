<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Api\V2010\Account\MessageList;
use Twilio\Rest\Client;

class WhatsappController extends Controller
{
    private $cypher_controller;
    public function __construct()
    {
        $this->cypher_controller = new CypherController();
    }
    /**
     * Envío de mensaje via WhatsApp.
     * Este metodo contiene la lógica para el envío de mensajes via WhatsApp,
     * conde la clase 'Client' hara uso de las credenciales necesarias ('SID' y 'Auth Token'),
     * las cuales se obtienen desde la configuración del proyecto 'config()'.4
     * Luego se usara el metodo 'MessageList::create()' para enviar el mensaje.
     *
     * @param string $message el mensaje que el usuario recibirá en WhatsApp.
     *
     * @throws Exception Si el proceso del envío del mensaje falla se agregará un mensaje de error en el archivo 'laravel.log'.
     *
     * @return true|false Retornara un true si el mensaje se envío exitosamente, un false si el proceso falla
     * o si el mensaje o teléfono no se especifica.
     *
     * @see config() Metodo para obtener las credenciales del servicio.
     * @see Client Clase que recibira las credenciales.
     * @see Client::$messages Variable que es una lista de mensajes
     * @see MessageList::create() Metodo para enviar el mensaje.
     */
    function sendMessage(string $message): bool
    {
        if(!$message){
            return false;
        }
        $twilio_sid = config('services.twilio.sid');
        $twilio_auth_token = config('services.twilio.auth_token');
        $twilio_whatsapp_number = 'whatsapp:'.str(config('services.twilio.whatsapp_number'));
        $to = 'whatsapp:'.str(config('services.twilio.test_number'));
        $client = new Client($twilio_sid, $twilio_auth_token);
        try {
            Log::info('Mensaje enviado: '.$message);
            $client->messages->create(
                $to,
                array(
                    'from' => $twilio_whatsapp_number,
                    'body' => $message
                )
            );
            return true;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
