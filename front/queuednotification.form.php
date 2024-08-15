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

Session::checkRight('queuednotification', READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$queuednotification = new QueuedNotification();

if (isset($_POST["delete"])) {
    $queuednotification->check($_POST["id"], DELETE);
    $queuednotification->delete($_POST);

    Event::log(
        $_POST["id"],
        QueuedNotification::class,
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $queuednotification->redirectToList();
} else if (isset($_POST["restore"])) {
    $queuednotification->check($_POST["id"], DELETE);
    $queuednotification->restore($_POST);

    Event::log(
        $_POST["id"],
        QueuedNotification::class,
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $queuednotification->redirectToList();
} else if (isset($_POST["purge"])) {
    $queuednotification->check($_POST["id"], PURGE);
    $queuednotification->delete($_POST, 1);

    Event::log(
        $_POST["id"],
        QueuedNotification::class,
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $queuednotification->redirectToList();
} else {
    $menus = ["admin", "queuednotification"];
    QueuedNotification::displayFullPageForItem($_GET["id"], $menus, $_GET);
}
