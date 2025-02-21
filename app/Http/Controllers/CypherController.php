<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class CypherController
 *
 * Es un controlador que tiene los metodos para encriptar y desencriptar texto.
 *
 * @package App\Http\Controllers
 */
class CypherController extends Controller
{

    /**
     * Encripta un texto utilizando el metodo AES-128-CBC.
     *
     * Este metodo encripta un texto plano usando una clave única que debe ser convertida
     * a un formato binario de 16 bytes (128 bits). También genera un vector de inicialización (IV)
     * único para cada operación, lo que asegura que incluso el mismo texto tendrá diferentes
     * salidas cifradas si se ejecuta varias veces.
     *
     * @param string $plaintext El texto plano que se desea encriptar.
     *
     * @return string El texto encriptado, codificado en base64.
     *
     * @see substr() Para manipular cadenas de texto.
     * @see hash() Para generar la clave a partir de un string.
     * @see openssl_random_pseudo_bytes() Para generar el vector de inicialización (IV).
     * @see openssl_cipher_iv_length() Para obtener la longitud requerida del IV.
     * @see openssl_encrypt() Para realizar la encriptación.
     * @see base64_encode() Para codificar la salida en base64.
     * @see config() Para obtener la configuración desde el servicio declarado.
     */
    public function encrypt(string $plaintext): string
    {
        $key_bytes_32 = substr(hash('sha256', config('services.cypher.key'), true), 0, 16);
        $initialization_vector = openssl_random_pseudo_bytes(openssl_cipher_iv_length(config('services.cypher.method')));
        $ciphertext = openssl_encrypt($plaintext, 'aes-128-cbc', $key_bytes_32, 0, $initialization_vector);
        return base64_encode($initialization_vector.$ciphertext);
    }

    /**
     * Desencripta un texto previamente cifrado con el metodo AES-128-CBC.
     *
     * Este metodo toma un texto cifrado que fue generado por el metodo `encrypt`, lo decodifica de base64
     * para extraer el vector de inicialización (IV) y el texto cifrado. Posteriormente, utiliza la misma
     * clave de 16 bytes y el metodo AES-128-CBC para realizar el proceso de desencriptación.
     *
     * @param string $encrypted_data El texto cifrado, codificado en base64, que se desea desencriptar.
     *
     * @return string Retorna el texto desencriptado en su forma original.
     *
     * @see substr() Para extraer el vector de inicialización y el texto cifrado.
     * @see hash() Para generar la clave a partir de la configuración.
     * @see base64_decode() Para decodificar el texto cifrado de base64.
     * @see config() Para obtener la configuración de la clave y el metodo.
     * @see openssl_cipher_iv_length() Para determinar la longitud del vector de inicialización.
     * @see openssl_decrypt() Para realizar el proceso de desencriptación.
     */
    public function decryptData($encrypted_data): string
    {
        $key_bytes_32 = substr(hash('sha256', config('services.cypher.key'), true), 0, 16);
        $decoded_data = base64_decode($encrypted_data);
        $initialization_vector_length = openssl_cipher_iv_length(config('services.cypher.method'));
        $initialization_vector = substr($decoded_data, 0, $initialization_vector_length);
        $cipher_text = substr($decoded_data, $initialization_vector_length);
        return openssl_decrypt($cipher_text, config('services.cypher.method'), $key_bytes_32, 0, $initialization_vector);
    }
}
