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

Session::checkRight("certificate", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$certificate = new Certificate();

if (isset($_POST["add"])) {
    $certificate->check(-1, CREATE, $_POST);

    if ($newID = $certificate->add($_POST)) {
        Event::log(
            $newID,
            "certificates",
            4,
            "inventory",
            sprintf(
                __('%1$s adds the item %2$s'),
                $_SESSION["glpiname"],
                $_POST["name"]
            )
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($certificate->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $certificate->check($_POST["id"], DELETE);
    $certificate->delete($_POST);

    Event::log(
        $_POST["id"],
        "certificates",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $certificate->redirectToList();
} else if (isset($_POST["restore"])) {
    $certificate->check($_POST["id"], DELETE);

    $certificate->restore($_POST);
    Event::log(
        $_POST["id"],
        "certificates",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $certificate->redirectToList();
} else if (isset($_POST["purge"])) {
    $certificate->check($_POST["id"], PURGE);

    $certificate->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "certificates",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $certificate->redirectToList();
} else if (isset($_POST["update"])) {
    $certificate->check($_POST["id"], UPDATE);

    $certificate->update($_POST);
    Event::log(
        $_POST["id"],
        "certificates",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ['management', 'certificate'];
    Certificate::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
