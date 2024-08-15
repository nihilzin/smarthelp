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

use Glpi\Http\Response;

include('../inc/includes.php');

$impact_item = new ImpactItem();

if (isset($_POST["update"])) {
    $id = $_POST["id"] ?? 0;

   // Can't update, id is missing
    if ($id === 0) {
        Response::sendError(400, "Can't update the target impact item, id is missing", Response::CONTENT_TYPE_TEXT_HTML);
    }

   // Load item and check rights
    $impact_item->getFromDB($id);
    Session::checkRight($impact_item->fields['itemtype']::$rightname, UPDATE);

   // Update item and back
    $impact_item->update($_POST);
    Html::redirect(Html::getBackUrl() . "#list");
}
