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

if (!defined('GLPI_ROOT')) {
    include('../inc/includes.php');
}

$link = new Change_User();
$item = new Change();

Session::checkLoginUser();
Html::popHeader(__('Email followup'), $_SERVER['PHP_SELF']);

if (isset($_POST["update"])) {
    $link->check($_POST["id"], UPDATE);

    $link->update($_POST);
    echo "<script type='text/javascript' >\n";
    echo "window.parent.location.reload();";
    echo "</script>";
} else if (isset($_POST['delete'])) {
    $link->check($_POST['id'], DELETE);
    $link->delete($_POST);

    Event::log(
        $link->fields['changes_id'],
        "change",
        4,
        "maintain",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an actor'), $_SESSION["glpiname"])
    );

    if ($item->can($link->fields["changes_id"], READ)) {
        Html::redirect(Change::getFormURLWithID($link->fields['changes_id']));
    }
    Session::addMessageAfterRedirect(
        __('You have been redirected because you no longer have access to this item'),
        true,
        ERROR
    );

    Html::redirect($CFG_GLPI["root_doc"] . "/front/change.php");
} else if (isset($_GET["id"])) {
    $link->showUserNotificationForm($_GET["id"]);
} else {
    Html::displayErrorAndDie('Lost');
}

Html::popFooter();
