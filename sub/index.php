<?php
ini_set('error_log', 'error_log');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Marzban.php';
require_once __DIR__ . '/../function.php';
require_once __DIR__ . '/../panels.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
    $scriptDir   = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    $token       = trim(ltrim(substr($requestPath, strlen($scriptDir)), '/'));

    if ($token === '') {
        echo "ERROR!";
        exit;
    }

    $nameloc = select("invoice", "*", "id_invoice", $token, "select");
    if (!is_array($nameloc) || empty($nameloc['Service_location']) || empty($nameloc['username'])) {
        echo "ERROR!";
        exit;
    }

    $ManagePanel = new ManagePanel();
    $DataUserOut = $ManagePanel->DataUser($nameloc['Service_location'], $nameloc['username']);
    if (!is_array($DataUserOut) || empty($DataUserOut['links']) || !is_iterable($DataUserOut['links'])) {
        echo "ERROR!";
        exit;
    }

    $config = "";
    foreach ($DataUserOut['links'] as $Links) {
        $config .= $Links . "\r\r";
    }
    echo $config;
} catch (Throwable $e) {
    echo "ERROR!";
}
