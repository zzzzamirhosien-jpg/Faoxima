<?php

class ApiAuthTest extends \PHPUnit\Framework\TestCase
{
    private $serverPid;
    private $baseUrl = 'http://localhost:8000/api';

    protected function setUp(): void
    {
        // For testing, start a local PHP server pointing to the root of the project
        $cmd = 'php -S localhost:8000 -t ' . escapeshellarg(__DIR__ . '/../') . ' > /dev/null 2>&1 & echo $!';
        $this->serverPid = (int) shell_exec($cmd);
        sleep(2); // Wait for the server to start
    }

    protected function tearDown(): void
    {
        // Stop the local server
        if ($this->serverPid) {
            exec('kill ' . $this->serverPid . ' > /dev/null 2>&1');
        }
        exec("kill $(lsof -t -i:8000) > /dev/null 2>&1");
    }

    private function requestEndpoint($endpoint, $tokenHeader)
    {
        $ch = curl_init($this->baseUrl . $endpoint);
        $headers = ['Content-Type: application/json'];

        if ($tokenHeader !== null) {
            if ($tokenHeader === '') {
                $headers[] = 'Token:'; // Sends empty string as token
            } else {
                $headers[] = 'Token: ' . $tokenHeader;
            }
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'code' => $httpCode,
            'response' => $response
        ];
    }

    public function testApiEndpointsRejectEmptyToken()
    {
        $endpoints = [
            '/log.php',
            '/statbot.php',
            '/users.php',
            '/settings.php',
        ];

        foreach ($endpoints as $endpoint) {
            // Test missing token
            $res = $this->requestEndpoint($endpoint, null);
            $this->assertEquals(403, $res['code'], "Endpoint $endpoint should return 403 without token");

            // Test empty token
            $res = $this->requestEndpoint($endpoint, '');
            $this->assertEquals(403, $res['code'], "Endpoint $endpoint should return 403 with empty token");

            // Test '0' token
            $res = $this->requestEndpoint($endpoint, '0');
            $this->assertEquals(403, $res['code'], "Endpoint $endpoint should return 403 with '0' token");
        }
    }
}
