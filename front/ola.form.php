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

Session::checkRight("slm", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}

$ola = new OLA();

if (isset($_POST["add"])) {
    $ola->check(-1, CREATE, $_POST);

    if ($newID = $ola->add($_POST)) {
        Event::log(
            $newID,
            "olas",
            4,
            "setup",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($ola->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $ola->check($_POST["id"], PURGE);
    $ola->delete($_POST, 1);

    Event::log(
        $_POST["id"],
        "olas",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $ola->redirectToList();
} else if (isset($_POST["update"])) {
    $ola->check($_POST["id"], UPDATE);
    $ola->update($_POST);

    Event::log(
        $_POST["id"],
        "olas",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["config", "slm", "ola"];
    OLA::displayFullPageForItem($_GET["id"], $menus);
}
