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

$right   = new Profile_User();

if (isset($_POST["add"])) {
    $right->check(-1, CREATE, $_POST);
    if ($right->add($_POST)) {
        Event::log(
            $_POST["users_id"],
            "users",
            4,
            "setup",
            //TRANS: %s is the user login
            sprintf(__('%s adds a user to an entity'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
