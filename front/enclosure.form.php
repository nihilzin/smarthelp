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

Session::checkRight("datacenter", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$enclosure = new Enclosure();

if (isset($_POST["add"])) {
    $enclosure->check(-1, CREATE, $_POST);

    if ($newID = $enclosure->add($_POST)) {
        Event::log(
            $newID,
            "enclosure",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($enclosure->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $enclosure->check($_POST["id"], DELETE);
    $enclosure->delete($_POST);

    Event::log(
        $_POST["id"],
        "enclosure",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $enclosure->redirectToList();
} else if (isset($_POST["restore"])) {
    $enclosure->check($_POST["id"], DELETE);

    $enclosure->restore($_POST);
    Event::log(
        $_POST["id"],
        "enclosure",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $enclosure->redirectToList();
} else if (isset($_POST["purge"])) {
    $enclosure->check($_POST["id"], PURGE);

    $enclosure->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "enclosure",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $enclosure->redirectToList();
} else if (isset($_POST["update"])) {
    $enclosure->check($_POST["id"], UPDATE);

    $enclosure->update($_POST);
    Event::log(
        $_POST["id"],
        "enclosure",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $options = [
        'withtemplate' => $_GET['withtemplate'],
        'formoptions'  => "data-track-changes=true"
    ];
    if (isset($_GET['position'])) {
        $options['position'] = $_GET['position'];
    }
    if (isset($_GET['room'])) {
        $options['room'] = $_GET['room'];
    }
    $menus = ["assets", "enclosure"];
    Enclosure::displayFullPageForItem($_GET['id'], $menus, $options);
}
