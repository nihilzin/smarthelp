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

include('../inc/includes.php');

Session::checkLoginUser();

$item = new Ticket_Contract();

if (isset($_POST["add"])) {
    if (!empty($_POST['tickets_id']) && empty($_POST['contracts_id'])) {
        $message = sprintf(
            __('Mandatory fields are not filled. Please correct: %s'),
            Contract::getTypeName(1)
        );
        Session::addMessageAfterRedirect($message, false, ERROR);
        Html::back();
    }
    if (empty($_POST['tickets_id']) && !empty($_POST['contracts_id'])) {
        $message = sprintf(
            __('Mandatory fields are not filled. Please correct: %s'),
            Ticket::getTypeName(1)
        );
        Session::addMessageAfterRedirect($message, false, ERROR);
        Html::back();
    }
    $item->check(-1, CREATE, $_POST);
    $item->add($_POST);

    Html::back();
}

Html::displayErrorAndDie("lost");
