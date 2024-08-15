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

Session::checkCentralAccess();

$team = new ProjectTeam();

if (isset($_POST["add"])) {
    $team->check(-1, CREATE, $_POST);
    if ($team->add($_POST)) {
        Event::log(
            $_POST["projects_id"],
            "project",
            4,
            "maintain",
            //TRANS: %s is the user login
            sprintf(__('%s adds a team member'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
