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
use Glpi\Csv\LogCsvExport;
use Glpi\Http\Response;

include('../../inc/includes.php');

// Read params
$itemtype = $_GET['itemtype']   ?? null;
$id       = $_GET['id']         ?? null;
$filter   = $_GET['filter']     ?? [];

Session::checkRight(Log::$rightname, READ);

// Validate itemtype
if (!is_a($itemtype, CommonDBTM::class, true)) {
    Response::sendError(400, "Invalid itemtype", Response::CONTENT_TYPE_TEXT_PLAIN);
}

// Validate id
$item = $itemtype::getById($id);
if (!$item || !$item->canViewItem()) {
    Response::sendError(400, "No item found for given id", Response::CONTENT_TYPE_TEXT_PLAIN);
}

// Validate filter
if (!is_array($filter)) {
    Response::sendError(400, "Invalid filter", Response::CONTENT_TYPE_TEXT_PLAIN);
}

CsvResponse::output(new LogCsvExport($item, $filter));
