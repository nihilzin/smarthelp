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

Session::checkRight("slm", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}

$slm = new SLM();

if (isset($_POST["add"])) {
    $slm->check(-1, CREATE);

    if ($newID = $slm->add($_POST)) {
        Event::log(
            $newID,
            "slms",
            4,
            "setup",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($slm->getLinkURL());
        }
    }
    Html::redirect($CFG_GLPI["root_doc"] . "/front/slm.php");
} else if (isset($_POST["purge"])) {
    $slm->check($_POST["id"], PURGE);
    $slm->delete($_POST, 1);

    Event::log(
        $_POST["id"],
        "slms",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $slm->redirectToList();
} else if (isset($_POST["update"])) {
    $slm->check($_POST["id"], UPDATE);
    $slm->update($_POST);

    Event::log(
        $_POST["id"],
        "slms",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["config", "slm"];
    SLM::displayFullPageForItem($_GET["id"], $menus);
}
