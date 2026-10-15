<?php

// A mock reproduction of the issue before the patch
function parseMockUpdate($json) {
    $update = json_decode($json, true);

    $text = $update["message"]["text"]  ?? '';

    // Convert function found in botapi.php
    function convertPersianNumbersToEnglish($string) {
        $persian_numbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english_numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($persian_numbers, $english_numbers, $string);
    }

    $text = convertPersianNumbersToEnglish($text);

    // Simulate what happens down the script, e.g. checking starting text
    if (strpos($text, '/') === 0) {
        echo "Command received.\n";
    }
}

// Simulated malicious or invalid edge-case telegram webhook payload where text is an array
$mockPayload = '{"update_id": 1234, "message": {"text": ["invalid", "array", "type"]}}';

echo "Running mock payload...\n";
parseMockUpdate($mockPayload);

?>
