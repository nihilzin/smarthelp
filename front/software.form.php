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

Session::checkRight("software", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$soft = new Software();
if (isset($_POST["add"])) {
    $soft->check(-1, CREATE, $_POST);

    if ($newID = $soft->add($_POST)) {
        Event::log(
            $newID,
            "software",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($soft->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $soft->check($_POST["id"], DELETE);
    $soft->delete($_POST);

    Event::log(
        $_POST["id"],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );

    $soft->redirectToList();
} else if (isset($_POST["restore"])) {
    $soft->check($_POST["id"], DELETE);

    $soft->restore($_POST);
    Event::log(
        $_POST["id"],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $soft->redirectToList();
} else if (isset($_POST["purge"])) {
    $soft->check($_POST["id"], PURGE);

    $soft->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $soft->redirectToList();
} else if (isset($_POST["update"])) {
    $soft->check($_POST["id"], UPDATE);

    $soft->update($_POST);
    Event::log(
        $_POST["id"],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["assets", "software"];
    Software::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
