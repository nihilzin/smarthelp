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

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["projects_id"])) {
    $_GET["projects_id"] = "";
}

$cost = new ProjectCost();
if (isset($_POST["add"])) {
    $cost->check(-1, CREATE, $_POST);

    if ($cost->add($_POST)) {
        Event::log(
            $_POST['projects_id'],
            "project",
            4,
            "maintain",
            //TRANS: %s is the user login
            sprintf(__('%s adds a cost'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $cost->check($_POST["id"], PURGE);

    if ($cost->delete($_POST, 1)) {
        Event::log(
            $cost->fields['projects_id'],
            "project",
            4,
            "maintain",
            //TRANS: %s is the user login
            sprintf(__('%s purges a cost'), $_SESSION["glpiname"])
        );
    }

    Html::redirect(Toolbox::getItemTypeFormURL('Project') . '?id=' . $cost->fields['projects_id']);
} else if (isset($_POST["update"])) {
    $cost->check($_POST["id"], UPDATE);

    if ($cost->update($_POST)) {
        Event::log(
            $cost->fields['projects_id'],
            "project",
            4,
            "maintain",
            //TRANS: %s is the user login
            sprintf(__('%s updates a cost'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

Html::displayErrorAndDie('Lost');
