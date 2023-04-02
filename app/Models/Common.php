<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Common extends Model
{
    use HasFactory;

    /**
     * Execute API request with given key, host, endpoint, and data.
     *
     * @param string $key The API key to use for authentication
     * @param string $host The API host to send the request to
     * @param string $endpoint The API endpoint to send the request to
     * @param array $data The data to include in the request
     * @return array The API response data as an array
     */
    public static function executeAPI (string $key, string $host, string $endpoint, array $data) {
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $key,
            'X-RapidAPI-Host' => $host,
        ])->get($endpoint, $data);

        // convert the response to an array
        $data = $response->json();
        return $data;
    }

    /**
    * Send an email using the PHP mail function.

    * @param string $to The email address to send the email to.
    * @param string $subject The subject of the email.
    * @param string $body The body of the email.
    * @return void
    */

    public static function sendMail ($to, $subject, $body) {
      
        // Set the headers of the email
        $headers = array(
            'From: hassanqureshi@mailinator.com',
            'Reply-To: hassanqureshi@mailinator.com'
        );

        // Send the email
        
        if (mail($to, $subject, $body, implode("\r\n", $headers))) {
            echo "Email sent successfully";
        } else {
            echo "Email could not be sent";
        }
    }
}
