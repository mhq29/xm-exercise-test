<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Common;

class CommonTest extends TestCase
{
    /**
     * Test executeApi method.
     *
     * @return void
     */
    public function testExecuteAPI()
    {
        $key = '11d5b0f54amsh2325f08132a251fp1828cejsn9691b6cd2125';
        $host = 'yh-finance.p.rapidapi.com';
        $endpoint = 'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data';
        $data = [
            'symbol' => 'AAPL',
            'region' => 'US',
            'from' => '2022-01-01',
            'to' => '2022-01-31',
        ];
        
        $common = new Common();
        $apiResponse = $common->executeAPI($key, $host, $endpoint, $data);
        
        // Assert that the API response data is not empty
        $this->assertNotEmpty($apiResponse);
        
        // Assert that the API response data is an array
        $this->assertIsArray($apiResponse);
        
        // Assert that the API response data contains the expected keys
        $this->assertArrayHasKey('prices', $apiResponse);
    }


    /**
     * Test sendMail method.
     *
     * @return void
     */
    public function testSendMail()
    {
        // Test email data
        $to = 'john.doe@example.com';
        $subject = 'Test email';
        $body = 'This is a test email.';

        // Send the email
        Common::sendMail($to, $subject, $body);

        // Assert that the email was sent successfully
        $this->expectOutputString('Email sent successfully');
    }
}
