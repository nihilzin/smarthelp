<?php

/*!
 * ---------------------------------------------------------------------
 *
 * Powered by Urich Souza 
 *
 * https://github.com/nihilzin
 *
 * @copyright 2023 Urich Souza and contributors.
 * 
 * ---------------------------------------------------------------------
 */


use Glpi\System\Status\StatusChecker;

include('./inc/includes.php');

// Force in normal mode
$_SESSION['glpi_use_mode'] = Session::NORMAL_MODE;

// Need to be used using :
// check_http -H servername -u /glpi/status.php -s GLPI_OK

$valid_response_types = ['text/plain', 'application/json'];
$fallback_response_type = 'text/plain';

if (!isset($_SERVER['HTTP_ACCEPT']) || !in_array($_SERVER['HTTP_ACCEPT'], $valid_response_types, true)) {
    $_SERVER['HTTP_ACCEPT'] = $fallback_response_type;
}

$format = $_SERVER['HTTP_ACCEPT'];
if (isset($_REQUEST['format'])) {
    switch ($_REQUEST['format']) {
        case 'json':
            $format = 'application/json';
            break;
        case 'plain':
            $format = 'text/plain';
            break;
    }
}

if ($format === 'text/plain') {
    Toolbox::deprecated('A saída de status de texto simples está obsoleta. Use o formato JSON definindo especificamente o cabeçalho Accept como "application/json". No futuro, a saída JSON será o padrão.');
}
header('Content-type: ' . $format);

if ($format === 'application/json') {
    echo json_encode(StatusChecker::getServiceStatus($_REQUEST['service'] ?? null, true, true));
} else {
    echo StatusChecker::getServiceStatus($_REQUEST['service'] ?? null, true, false);
}
