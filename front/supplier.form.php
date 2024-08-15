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

Session::checkRight("contact_enterprise", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = -1;
}


$ent = new Supplier();

if (isset($_POST["add"])) {
    $ent->check(-1, CREATE, $_POST);

    if ($newID = $ent->add($_POST)) {
        Event::log(
            $newID,
            "suppliers",
            4,
            "financial",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($ent->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $ent->check($_POST["id"], DELETE);
    $ent->delete($_POST);
    Event::log(
        $_POST["id"],
        "suppliers",
        4,
        "financial",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $ent->redirectToList();
} else if (isset($_POST["restore"])) {
    $ent->check($_POST["id"], DELETE);
    $ent->restore($_POST);
    Event::log(
        $_POST["id"],
        "suppliers",
        4,
        "financial",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );

    $ent->redirectToList();
} else if (isset($_POST["purge"])) {
    $ent->check($_POST["id"], PURGE);
    $ent->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "suppliers",
        4,
        "financial",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );

    $ent->redirectToList();
} else if (isset($_POST["update"])) {
    $ent->check($_POST["id"], UPDATE);
    $ent->update($_POST);
    Event::log(
        $_POST["id"],
        "suppliers",
        4,
        "financial",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["management", "supplier"];
    Supplier::displayFullPageForItem($_GET["id"], $menus, [
        'formoptions'  => "data-track-changes=true"
    ]);
}
