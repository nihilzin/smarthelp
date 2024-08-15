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

Session::checkRight("notification", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$notification = new Notification();
if (isset($_POST["add"])) {
    $notification->check(-1, CREATE, $_POST);

    $newID = $notification->add($_POST);
    Event::log(
        $newID,
        "notifications",
        4,
        "notification",
        sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
    );
    Html::redirect($_SERVER['PHP_SELF'] . "?id=$newID");
} else if (isset($_POST["purge"])) {
    $notification->check($_POST["id"], PURGE);
    $notification->delete($_POST, 1);

    Event::log(
        $_POST["id"],
        "notifications",
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $notification->redirectToList();
} else if (isset($_POST["update"])) {
    $notification->check($_POST["id"], UPDATE);

    $notification->update($_POST);
    Event::log(
        $_POST["id"],
        "notifications",
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["config", "notification", "notification"];
    Notification::displayFullPageForItem($_GET["id"], $menus);
}
