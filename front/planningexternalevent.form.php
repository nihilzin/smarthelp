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

include('../inc/includes.php');

Session::checkRight("planning", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}

$extevent = new PlanningExternalEvent();

if (isset($_POST["add"])) {
    $extevent->check(-1, CREATE, $_POST);

    if ($newID = $extevent->add($_POST)) {
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($extevent->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $extevent->check($_POST["id"], DELETE);
    $extevent->delete($_POST);
    $extevent->redirectToList();
} else if (isset($_POST["restore"])) {
    $extevent->check($_POST["id"], DELETE);
    $extevent->restore($_POST);
    $extevent->redirectToList();
} else if (isset($_POST["purge"])) {
    $extevent->check($_POST["id"], PURGE);
    $extevent->delete($_POST, 1);
    $extevent->redirectToList();
} else if (isset($_POST["purge_instance"])) {
    $extevent->check($_POST["id"], PURGE);
    $extevent->deleteInstance((int) $_POST["id"], $_POST['day']);
    $extevent->redirectToList();
} else if (isset($_POST["update"])) {
    $extevent->check($_POST["id"], UPDATE);
    $extevent->update($_POST);
    Html::back();
} else {
    $menus = ["helpdesk", "planning", "external"];
    PlanningExternalEvent::displayFullPageForItem($_GET["id"], $menus);
}
