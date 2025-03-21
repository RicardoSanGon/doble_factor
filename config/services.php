<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    //Servicio para envío de mensajes via WhatsApp
    'twilio' => [
        'sid' => env('TWILIO_SID'), //SID de la cuenta para permitir el uso del servicio
        'auth_token' => env('TWILIO_AUTH_TOKEN'), //Token para autenticar
        'whatsapp_number' => env('TWILIO_PHONE_NUMBER'), //Número que hara uso el servicio Twilio
        'test_number' => env('PHONE_TEST'), //Número de prueba para enviar mensajes
    ],
    //Servicio para encruptacion de texto
    'cypher' => [
        'key' => env('KEY_CYPHER'), //LLave unica que usara la encriptación
        'method' => env('CYPHER_METHOD'), //El metodo que hara uso la encriptación.
    ],
];
