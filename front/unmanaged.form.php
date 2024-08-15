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

Session::checkRight("unmanaged", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$unmanaged = new Unmanaged();
if (isset($_POST["add"])) {
    $unmanaged->check(-1, CREATE, $_POST);

    if ($newID = $unmanaged->add($_POST)) {
        Event::log(
            $newID,
            "Unmanaged",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($unmanaged->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $unmanaged->check($_POST["id"], DELETE);
    $unmanaged->delete($_POST);

    Event::log(
        $_POST["id"],
        "unmanaged",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );

    $unmanaged->redirectToList();
} else if (isset($_POST["restore"])) {
    $unmanaged->check($_POST["id"], DELETE);

    $unmanaged->restore($_POST);
    Event::log(
        $_POST["id"],
        "unmanaged",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $unmanaged->redirectToList();
} else if (isset($_POST["purge"])) {
    $unmanaged->check($_POST["id"], PURGE);

    $unmanaged->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "unmanaged",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $unmanaged->redirectToList();
} else if (isset($_POST["update"])) {
    $unmanaged->check($_POST["id"], UPDATE);

    $unmanaged->update($_POST);
    Event::log(
        $_POST["id"],
        "unmanaged",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["assets", "unmanaged"];
    Unmanaged::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
