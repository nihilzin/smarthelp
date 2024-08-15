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

$language = new NotificationTemplateTranslation();

if (isset($_POST["add"])) {
    $language->check(-1, CREATE, $_POST);
    $newID = $language->add($_POST);
    Event::log(
        $newID,
        "notificationtemplatetranslations",
        4,
        "notification",
        sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["language"])
    );
    Html::back();
} else if (isset($_POST["purge"])) {
    $language->check($_POST["id"], PURGE);
    $language->delete($_POST, 1);

    Event::log(
        $_POST["id"],
        "notificationtemplatetranslations",
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $language->redirectToList();
} else if (isset($_POST["update"])) {
    $language->check($_POST["id"], UPDATE);
    $language->update($_POST);

    Event::log(
        $_POST["id"],
        "notificationtemplatetranslations",
        4,
        "notification",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $template = new NotificationTemplate();
    if (!isset($_GET["notificationtemplates_id"]) && $_GET["id"] != '') {
        $language->getFromDB($_GET["id"]);
        $_GET["notificationtemplates_id"] = $language->fields["notificationtemplates_id"];
    }
    $template->getFromDB($_GET["notificationtemplates_id"]);
    $_SESSION['glpilisturl'][NotificationTemplateTranslation::getType()] = $template->getLinkURL();

    if ($_GET["id"] == '') {
        $options = [
            "notificationtemplates_id" => $_GET["notificationtemplates_id"]
        ];
    } else {
        $options = [];
    }

    $menus = ["config", "notification", "notificationtemplate"];
    NotificationTemplateTranslation::displayFullPageForItem(
        $_GET["id"],
        $menus,
        $options
    );
}
