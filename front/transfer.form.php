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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

Session::checkRight("transfer", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}

$transfer = new Transfer();

if (isset($_POST["add"])) {
    $transfer->check(-1, CREATE, $_POST);

    $newID = $transfer->add($_POST);
    Event::log(
        $newID,
        "transfers",
        4,
        "setup",
        sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
    );
    Html::back();
} else if (isset($_POST["purge"])) {
    $transfer->check($_POST["id"], PURGE);

    $transfer->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "transfers",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    Html::redirect($CFG_GLPI["root_doc"] . "/front/transfer.php");
} else if (isset($_POST["update"])) {
    $transfer->check($_POST["id"], UPDATE);

    $transfer->update($_POST);
    Event::log(
        $_POST["id"],
        "transfers",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
}

$menus = ['admin', 'rule', 'transfer'];
Transfer::displayFullPageForItem($_GET["id"], $menus, [
    'target' => $transfer->getFormURL()
]);
