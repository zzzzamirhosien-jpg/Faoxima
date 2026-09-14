<?php


declare(strict_types=1);


if (!defined('FAOXIMA_SKIP_BOTAPI_ROUTER')) {
    define('FAOXIMA_SKIP_BOTAPI_ROUTER', true);
}

ob_start();

@ini_set('display_errors',         '0');
@ini_set('display_startup_errors', '0');
@ini_set('log_errors',             '1');
error_reporting(E_ALL);


$GLOBALS['__verify_response_sent'] = false;

function __verify_emit(int $http, array $payload): void
{
    while (ob_get_level() > 0) {
        @ob_end_clean();
    }
    if (!headers_sent()) {
        http_response_code($http);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, max-age=0');
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $GLOBALS['__verify_response_sent'] = true;
}

register_shutdown_function(static function () {
    if (!empty($GLOBALS['__verify_response_sent'])) {
        return;
    }
    $err = error_get_last();
    $fatal = [E_ERROR, E_PARSE, E_COMPILE_ERROR, E_CORE_ERROR, E_USER_ERROR];
    if (is_array($err) && in_array($err['type'], $fatal, true)) {
        error_log('PHP fatal error in verify.php: ' . $err['message'] . ' in ' . $err['file'] . ':' . $err['line']);
        __verify_emit(500, [
            'status' => false,
            'msg'    => 'An internal server error occurred',
            'token'  => null,
        ]);
        return;
    }
    __verify_emit(500, [
        'status' => false,
        'msg'    => 'An internal server error occurred',
        'token'  => null,
    ]);
});

try {
    require_once __DIR__ . '/lib/Bootstrap.php';
    require_once __DIR__ . '/handlers/VerifyHandler.php';

    while (ob_get_level() > 0) {
        @ob_end_clean();
    }
    ob_start();

    VerifyHandler::run();

    __verify_emit(500, [
        'status' => false,
        'msg'    => 'An internal server error occurred',
        'token'  => null,
    ]);
} catch (Throwable $e) {
    error_log('Exception in verify.php: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    __verify_emit(500, [
        'status' => false,
        'msg'    => 'An internal server error occurred',
        'token'  => null,
    ]);
}

