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

$ticket_ticket = new Ticket_Ticket();

Session::checkCentralAccess();

if (isset($_POST['purge'])) {
    $ticket_ticket->check($_POST['id'], PURGE);

    $ticket_ticket->delete($_POST, 1);

    Event::log(
        $_POST['tickets_id'],
        "ticket",
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s purges link between tickets'), $_SESSION["glpiname"])
    );
    Html::redirect(Ticket::getFormURLWithID($_POST['tickets_id']));
}
Html::displayErrorAndDie("lost");
