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

$AJAX_INCLUDE = 1;
include('../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight('ticket', UPDATE);

if ($_POST["actortype"] > 0) {
    $ticket = new Ticket();
    $rand   = mt_rand();
    $ticket->showActorAddForm(
        $_POST["actortype"],
        $rand,
        $_SESSION['glpiactive_entity'],
        [],
        true,
        true,
        false
    );
    echo "&nbsp;<input type='submit' name='add_actor' class='btn btn-primary' value=\"" . _sx('button', 'Add') . "\">";
}
