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

$group_user = new Group_User();

if (isset($_POST["add"])) {
    $group_user->check(-1, CREATE, $_POST);
    if ($group_user->add($_POST)) {
        Event::log(
            $_POST["groups_id"],
            "groups",
            4,
            "setup",
            //TRANS: %s is the user login
            sprintf(__('%s adds a user to a group'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
