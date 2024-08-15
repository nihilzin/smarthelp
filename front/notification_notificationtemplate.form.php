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

//Html::back();
//
if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$notiftpl = new Notification_NotificationTemplate();
if (isset($_POST["add"])) {
    $notiftpl->check(-1, CREATE, $_POST);

    if ($notiftpl->add($_POST)) {
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($notiftpl->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $notiftpl->check($_POST["id"], PURGE);
    $notiftpl->delete($_POST, 1);
    Html::redirect(Notification::getFormURLWithID($notiftpl->fields['notifications_id']));
} else if (isset($_POST["update"])) {
    $notiftpl->check($_POST["id"], UPDATE);

    $notiftpl->update($_POST);
    Html::back();
} else {
    $params = [];
    if (isset($_GET['notifications_id'])) {
        $params['notifications_id'] = $_GET['notifications_id'];
    }

    $menus = ["config", "notification", "notifications_notificationtemplates"];
    Notification_NotificationTemplate::displayFullPageForItem(
        $_GET['id'],
        $menus,
        $params
    );
}
