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

Session::checkRight("monitor", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$monitor = new Monitor();

if (isset($_POST["add"])) {
    $monitor->check(-1, CREATE, $_POST);

    if ($newID = $monitor->add($_POST)) {
        Event::log(
            $newID,
            "monitors",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($monitor->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $monitor->check($_POST["id"], DELETE);
    $monitor->delete($_POST);

    Event::log(
        $_POST["id"],
        "monitors",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $monitor->redirectToList();
} else if (isset($_POST["restore"])) {
    $monitor->check($_POST["id"], DELETE);

    $monitor->restore($_POST);
    Event::log(
        $_POST["id"],
        "monitors",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $monitor->redirectToList();
} else if (isset($_POST["purge"])) {
    $monitor->check($_POST["id"], PURGE);

    $monitor->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "monitors",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $monitor->redirectToList();
} else if (isset($_POST["update"])) {
    $monitor->check($_POST["id"], UPDATE);

    $monitor->update($_POST);
    Event::log(
        $_POST["id"],
        "monitors",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_POST["unglobalize"])) {
    $monitor->check($_POST["id"], UPDATE);

    Computer_Item::unglobalizeItem($monitor);
    Event::log(
        $_POST["id"],
        "monitors",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s sets unitary management'), $_SESSION["glpiname"])
    );

    Html::redirect($monitor->getFormURLWithID($_POST["id"]));
} else {
    $menus = ["assets", "monitor"];
    Monitor::displayFullPageForItem($_GET['id'], $menus, [
        'loaded'       => true,
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true",
    ]);
}
