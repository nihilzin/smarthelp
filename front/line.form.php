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

Session::checkRight("line", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$line = new Line();

if (isset($_POST["add"])) {
    $line->check(-1, CREATE, $_POST);

    if ($newID = $line->add($_POST)) {
        Event::log(
            $newID,
            "lines",
            4,
            "financial",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($line->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $line->check($_POST["id"], DELETE);
    $line->delete($_POST);

    Event::log(
        $_POST["id"],
        "lines",
        4,
        "financial",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $line->redirectToList();
} else if (isset($_POST["restore"])) {
    $line->check($_POST["id"], DELETE);

    $line->restore($_POST);
    Event::log(
        $_POST["id"],
        "lines",
        4,
        "financial",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $line->redirectToList();
} else if (isset($_POST["purge"])) {
    $line->check($_POST["id"], PURGE);

    $line->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "lines",
        4,
        "financial",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $line->redirectToList();
} else if (isset($_POST["update"])) {
    $line->check($_POST["id"], UPDATE);

    $line->update($_POST);
    Event::log(
        $_POST["id"],
        "lines",
        4,
        "financial",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["management", "line"];
    Line::displayFullPageForItem($_GET["id"], $menus, [
        'formoptions'  => "data-track-changes=true"
    ]);
}
