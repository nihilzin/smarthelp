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

Session::checkCentralAccess();

if (empty($_GET["id"])) {
    $_GET["id"] = '';
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = '';
}

$record = new DomainRecord();

if (isset($_POST["add"])) {
    $record->check(-1, CREATE, $_POST);
    $newID = $record->add($_POST);
    if ($_SESSION['glpibackcreated'] && !isset($_POST['_in_modal'])) {
        Html::redirect($record->getFormURLWithID($newID));
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $record->check($_POST['id'], DELETE);
    $record->delete($_POST);
    $record->redirectToList();
} else if (isset($_POST["restore"])) {
    $record->check($_POST['id'], PURGE);
    $record->restore($_POST);
    $record->redirectToList();
} else if (isset($_POST["purge"])) {
    $record->check($_POST['id'], PURGE);
    $record->delete($_POST, 1);
    $record->redirectToList();
} else if (isset($_POST["update"])) {
    $record->check($_POST['id'], UPDATE);
    $record->update($_POST);
    Html::back();
} else if (isset($_GET['_in_modal'])) {
    Html::popHeader(DomainRecord::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], true);
    $record->showForm($_GET["id"], ['domains_id' => $_GET['domains_id'] ?? null]);
    Html::popFooter();
} else {
    $menus = ["management", "domain", "domainrecord"];
    DomainRecord::displayFullPageForItem($_GET["id"], $menus, [
        'domains_id'   => $_GET['domains_id'] ?? null,
        'withtemplate' => $_GET["withtemplate"]
    ]);
}
