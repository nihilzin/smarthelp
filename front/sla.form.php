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

$sla = new SLA();

if (isset($_POST["add"])) {
    $sla->check(-1, CREATE, $_POST);

    if ($newID = $sla->add($_POST)) {
        Event::log(
            $newID,
            "slas",
            4,
            "setup",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($sla->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $sla->check($_POST["id"], PURGE);
    $sla->delete($_POST, 1);

    Event::log(
        $_POST["id"],
        "slas",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $sla->redirectToList();
} else if (isset($_POST["update"])) {
    $sla->check($_POST["id"], UPDATE);
    $sla->update($_POST);

    Event::log(
        $_POST["id"],
        "slas",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["config", "slm", "sla"];
    SLA::displayFullPageForItem($_GET["id"], $menus);
}
