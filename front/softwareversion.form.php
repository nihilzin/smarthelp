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

Session::checkRight("software", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["softwares_id"])) {
    $_GET["softwares_id"] = "";
}

$version = new SoftwareVersion();

if (isset($_POST["add"])) {
    $version->check(-1, CREATE, $_POST);

    if ($newID = $version->add($_POST)) {
        Event::log(
            $_POST['softwares_id'],
            "software",
            4,
            "inventory",
            //TRANS: %s is the user login, %2$s is the version id
            sprintf(__('%1$s adds the version %2$s'), $_SESSION["glpiname"], $newID)
        );
        Html::redirect(Software::getFormURLWithID($version->fields['softwares_id']));
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $version->check($_POST['id'], PURGE);
    $version->delete($_POST, 1);
    Event::log(
        $version->fields['softwares_id'],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login, %2$s is the version id
        sprintf(__('%1$s purges the version %2$s'), $_SESSION["glpiname"], $_POST["id"])
    );
    Html::redirect(Software::getFormURLWithID($version->fields['softwares_id']));
} else if (isset($_POST["update"])) {
    $version->check($_POST['id'], UPDATE);

    $version->update($_POST);
    Event::log(
        $version->fields['softwares_id'],
        "software",
        4,
        "inventory",
        //TRANS: %s is the user login, %2$s is the version id
        sprintf(__('%1$s updates the version %2$s'), $_SESSION["glpiname"], $_POST["id"])
    );
    Html::back();
} else {
    $menus = ["assets", "software"];
    SoftwareVersion::displayFullPageForItem($_GET["id"], $menus, [
        'softwares_id' => $_GET["softwares_id"]
    ]);
}
