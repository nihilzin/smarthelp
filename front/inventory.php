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

use Glpi\Inventory\Conf;
use Glpi\Inventory\Request;

$SECURITY_STRATEGY = 'no_check'; // allow anonymous requests from inventory agent

if (!defined('GLPI_ROOT')) {
    include(__DIR__ . '/../inc/includes.php');
}

$conf = new Conf();
if ($conf->enabled_inventory != 1) {
    die("Inventory is disabled");
}

$inventory_request = new Request();
$inventory_request->handleHeaders();

$refused = new RefusedEquipment();

$handle = true;
$contents = '';
if (isset($_GET['refused'])) {
    Session::checkRight("config", READ);
    if ($refused->getFromDB($_GET['refused']) && ($inventory_file = $refused->getInventoryFileName()) !== null) {
        $contents = file_get_contents($inventory_file);
    } else {
        trigger_error(
            sprintf('Invalid RefusedEquipment "%s" or inventory file missing', $_GET['refused']),
            E_USER_WARNING
        );
    }
} else if (!isCommandLine() && $_SERVER['REQUEST_METHOD'] != 'POST') {
    if (isset($_GET['action']) && $_GET['action'] == 'getConfig') {
        /**
         * Even if Fusion protocol is not supported for getConfig requests, they
         * should be handled and answered with a json content type
         */
        $inventory_request->handleContentType('application/json');
        $inventory_request->addError('Protocol not supported', 400);
    } else {
        // Method not allowed answer without content
        $inventory_request->addError(null, 405);
    }
    $handle = false;
} else {
    if (isCommandLine()) {
        $f = fopen('php://stdin', 'r');
        $contents = '';
        while ($line = fgets($f)) {
            $contents .= $line;
        }
        fclose($f);
    } else {
        $contents = file_get_contents("php://input");
    }
}

if ($handle === true) {
    try {
        $inventory_request->handleRequest($contents);
    } catch (\Throwable $e) {
        $inventory_request->addError($e->getMessage());
    }
}

$inventory_request->handleMessages();

if (isset($_GET['refused'])) {
    $redirect_url = $refused->handleInventoryRequest($inventory_request);
    Html::redirect($redirect_url);
} else {
    if (isCommandLine()) {
        exit(0);
    }
    $headers = $inventory_request->getHeaders(true);
    http_response_code($inventory_request->getHttpResponseCode());
    foreach ($headers as $key => $value) {
        header(sprintf('%1$s: %2$s', $key, $value));
    }
    echo $inventory_request->getResponse();
}
