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
use Glpi\Csv\StatCsvExport;
use Glpi\Http\Response;
use Glpi\Stat\StatData;

include('../inc/includes.php');

// Check rights
Session::checkRight("statistic", READ);

// Read params
$statdata_itemtype = $_UGET['statdata_itemtype'] ?? null;

// Validate stats itemtype
if (!is_a($statdata_itemtype, StatData::class, true)) {
    Response::sendError(400, "Invalid stats itemtype", Response::CONTENT_TYPE_TEXT_PLAIN);
}

// Get data and output csv
$graph_data = new $statdata_itemtype($_GET);
CsvResponse::output(
    new StatCsvExport($graph_data->getSeries(), $graph_data->getOptions())
);
