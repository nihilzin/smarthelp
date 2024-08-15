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

$inquest = new TicketSatisfaction();

if (isset($_POST["update"])) {
    $inquest->check($_POST["tickets_id"], UPDATE);
    $inquest->update($_POST);

    Event::log(
        $inquest->getField('tickets_id'),
        "ticket",
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
}

Html::displayErrorAndDie('Lost');
