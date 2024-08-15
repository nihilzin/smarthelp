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

Session::checkRight("agent", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$agent = new Agent();
// delete an agent
if (isset($_POST["delete"])) {
    $agent->check($_POST['id'], DELETE);
    $ok = $agent->delete($_POST);
    if ($ok) {
        Event::log(
            $_POST["id"],
            "agents",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
        );
    }
    $agent->redirectToList();
} else if (isset($_POST["restore"])) {
    $agent->check($_POST['id'], DELETE);
    if ($agent->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "agents",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s restores an item'), $_SESSION["glpiname"])
        );
    }
    $agent->redirectToList();
} else if (isset($_POST["purge"])) {
    $agent->check($_POST['id'], PURGE);
    if ($agent->delete($_POST, 1)) {
        Event::log(
            $_POST["id"],
            "agents",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $agent->redirectToList();

   //update an agent
} else if (isset($_POST["update"])) {
    $agent->check($_POST['id'], UPDATE);
    $agent->update($_POST);
    Event::log(
        $_POST["id"],
        "agents",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {//print agent information
    $menus = ["admin", "glpi\inventory\inventory", "agent"];
    Agent::displayFullPageForItem((int) $_GET['id'], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true",
    ]);
}
