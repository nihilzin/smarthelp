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

use Glpi\Csv\CsvResponse;
use Glpi\Csv\ImpactCsvExport;

include('../inc/includes.php');

$itemtype = $_GET['itemtype'] ?? '';
$items_id = $_GET['items_id'] ?? '';

// Check for mandatory params
if (empty($itemtype) || empty($items_id)) {
    http_response_code(400);
    die();
}

// Check right
Session::checkRight($itemtype::$rightname, READ);

// Load item
$item = new $itemtype();
$item->getFromDB($items_id);

CsvResponse::output(new ImpactCsvExport($item));
