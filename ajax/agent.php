<?php

/**
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

$AJAX_INCLUDE = 1;
include('../inc/includes.php');
header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (isset($_POST['action']) && isset($_POST['id'])) {
    $agent = new Agent();
    if (!$agent->getFromDB($_POST['id']) || !$agent->canView()) {
        Response::sendError(404, 'Unable to load agent #' . $_POST['id']);
        return;
    }
    $answer = [];

    Session::writeClose();
    switch ($_POST['action']) {
        case Agent::ACTION_INVENTORY:
            $answer = $agent->requestInventory();
            break;

        case Agent::ACTION_STATUS:
            $answer = $agent->requestStatus();
            break;
    }

    echo json_encode($answer);
}
