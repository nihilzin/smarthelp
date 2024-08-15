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

Session::checkCentralAccess();

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["computers_id"])) {
    $_GET["computers_id"] = "";
}

$antivirus = new ComputerAntivirus();
if (isset($_POST["add"])) {
    $antivirus->check(-1, CREATE, $_POST);

    if ($antivirus->add($_POST)) {
        Event::log(
            $_POST['computers_id'],
            "computers",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s adds an antivirus'), $_SESSION["glpiname"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($antivirus->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $antivirus->check($_POST["id"], PURGE);

    if ($antivirus->delete($_POST, 1)) {
        Event::log(
            $antivirus->fields['computers_id'],
            "computers",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges an antivirus'), $_SESSION["glpiname"])
        );
    }
    $computer = new Computer();
    $computer->getFromDB($antivirus->fields['computers_id']);
    Html::redirect(Toolbox::getItemTypeFormURL('Computer') . '?id=' . $antivirus->fields['computers_id'] .
                  ($computer->fields['is_template'] ? "&withtemplate=1" : ""));
} else if (isset($_POST["update"])) {
    $antivirus->check($_POST["id"], UPDATE);

    if ($antivirus->update($_POST)) {
        Event::log(
            $antivirus->fields['computers_id'],
            "computers",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s updates an antivirus'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $menus = ["assets", "computer"];
    ComputerAntivirus::displayFullPageForItem($_GET["id"], $menus, [
        'computers_id' => $_GET["computers_id"]
    ]);
}
