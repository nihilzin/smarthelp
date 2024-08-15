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

$computer_vm = new ComputerVirtualMachine();
if (isset($_POST["add"])) {
    $computer_vm->check(-1, CREATE, $_POST);

    if ($computer_vm->add($_POST)) {
        Event::log(
            $_POST['computers_id'],
            "computers",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s adds a virtual machine'), $_SESSION["glpiname"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($computer_vm->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $computer_vm->check($_POST["id"], DELETE);
    $computer_vm->delete($_POST);

    Event::log(
        $_POST["id"],
        "computers",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $computer = new Computer();
    $computer->getFromDB($computer_vm->fields['computers_id']);
    Html::redirect(Toolbox::getItemTypeFormURL('Computer') . '?id=' . $computer_vm->fields['computers_id'] .
                  ($computer->fields['is_template'] ? "&withtemplate=1" : ""));
} else if (isset($_POST["purge"])) {
    $computer_vm->check($_POST["id"], PURGE);

    if ($computer_vm->delete($_POST, 1)) {
        Event::log(
            $computer_vm->fields['computers_id'],
            "computers",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges a virtual machine'), $_SESSION["glpiname"])
        );
    }
    $computer = new Computer();
    $computer->getFromDB($computer_vm->fields['computers_id']);
    Html::redirect(Toolbox::getItemTypeFormURL('Computer') . '?id=' . $computer_vm->fields['computers_id'] .
                  ($computer->fields['is_template'] ? "&withtemplate=1" : ""));
} else if (isset($_POST["update"])) {
    $computer_vm->check($_POST["id"], UPDATE);

    if ($computer_vm->update($_POST)) {
        Event::log(
            $computer_vm->fields['computers_id'],
            "computers",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s updates a virtual machine'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else if (isset($_POST["restore"])) {
    $computer_vm->check($_POST['id'], DELETE);
    if ($computer_vm->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "computers",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s restores a virtual machine'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $menus = ["assets", "computer"];
    ComputerVirtualMachine::displayFullPageForItem($_GET["id"], $menus, [
        'computers_id' => $_GET["computers_id"]
    ]);
}
