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

use Glpi\Event;

include('../inc/includes.php');

Session::checkLoginUser();

$item = new Change_Ticket();
if (isset($_POST["add"])) {
    if (!empty($_POST['tickets_id']) && empty($_POST['changes_id'])) {
        $message = sprintf(
            __('Mandatory fields are not filled. Please correct: %s'),
            Change::getTypeName(1)
        );
        Session::addMessageAfterRedirect($message, false, ERROR);
        Html::back();
    }
    if (empty($_POST['tickets_id']) && !empty($_POST['changes_id'])) {
        $message = sprintf(
            __('Mandatory fields are not filled. Please correct: %s'),
            Ticket::getTypeName(1)
        );
        Session::addMessageAfterRedirect($message, false, ERROR);
        Html::back();
    }
    $item->check(-1, CREATE, $_POST);

    if ($item->add($_POST)) {
        Event::log(
            $_POST["changes_id"],
            "change",
            4,
            "maintain",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
