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

$room = new DCRoom();

if (isset($_POST["add"])) {
    $room->check(-1, CREATE, $_POST);

    if ($newID = $room->add($_POST)) {
        Event::log(
            $newID,
            "dcrooms",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($room->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $room->check($_POST["id"], DELETE);
    $room->delete($_POST);

    Event::log(
        $_POST["id"],
        "dcrooms",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $room->redirectToList();
} else if (isset($_POST["restore"])) {
    $room->check($_POST["id"], DELETE);

    $room->restore($_POST);
    Event::log(
        $_POST["id"],
        "dcrooms",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $room->redirectToList();
} else if (isset($_POST["purge"])) {
    $room->check($_POST["id"], PURGE);

    $room->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "dcrooms",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $room->redirectToList();
} else if (isset($_POST["update"])) {
    $room->check($_POST["id"], UPDATE);

    $room->update($_POST);
    Event::log(
        $_POST["id"],
        "dcrooms",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $options = [
        'id' => $_GET["id"],
    ];
    if (
        isset($_REQUEST['_add_fromitem'])
        && isset($_REQUEST['datacenters_id'])
    ) {
        $options['datacenters_id'] = $_REQUEST['datacenters_id'];
        $datacenter = new Datacenter();
        $datacenter->getFromDB($options['datacenters_id']);
        $options['locations_id'] = $datacenter->fields['locations_id'];
    }
    $menus = ["management", "datacenter", "dcroom"];
    DCRoom::displayFullPageForItem($_GET["id"], $menus, $options);
}
