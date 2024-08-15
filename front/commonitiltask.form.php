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

/**
 * Following variables have to be defined before inclusion of this file:
 * @var CommonITILTask $task
 */

use Glpi\Event;

/** @var \DBmysql $DB */
global $DB;

// autoload include in objecttask.form (tickettask, problemtask,...)
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}
Session::checkCentralAccess();

if (!($task instanceof CommonITILTask)) {
    Html::displayErrorAndDie('');
}
if (!$task->canView()) {
    Html::displayRightError();
}

$itemtype = $task->getItilObjectItemType();
$fk       = getForeignKeyFieldForItemType($itemtype);

$track = new $itemtype();
$track->getFromDB($task->getField($fk));

$redirect = null;
$handled = false;

if (isset($_POST["add"])) {
    $task->check(-1, CREATE, $_POST);
    $task->add($_POST);

    Event::log(
        $task->getField($fk),
        strtolower($itemtype),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s adds a task'), $_SESSION["glpiname"])
    );
    $redirect = $itemtype::getFormURLWithID($task->getField($fk));
    $handled = true;
} else if (isset($_POST["purge"])) {
    $task->check($_POST['id'], PURGE);
    $task->delete($_POST, 1);

    Event::log(
        $task->getField($fk),
        strtolower($itemtype),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s purges a task'), $_SESSION["glpiname"])
    );
    Html::redirect($itemtype::getFormURLWithID($task->getField($fk)));
} else if (isset($_POST["update"])) {
    $task->check($_POST["id"], UPDATE);
    $task->update($_POST);

    Event::log(
        $task->getField($fk),
        strtolower($itemtype),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s updates a task'), $_SESSION["glpiname"])
    );
    $redirect = $itemtype::getFormURLWithID($task->getField($fk));
    $handled = true;
} else if (isset($_POST["unplan"])) {
    $task->check($_POST["id"], UPDATE);
    $task->unplan();

    Event::log(
        $task->getField($fk),
        strtolower($itemtype),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s unplans a task'), $_SESSION["glpiname"])
    );
    $redirect = $itemtype::getFormURLWithID($task->getField($fk));
    $handled = true;
}

if ($handled) {
    if (isset($_POST['kb_linked_id'])) {
       //if followup should be linked to selected KB entry
        $params = [
            'knowbaseitems_id' => $_POST['kb_linked_id'],
            'itemtype'         => $itemtype,
            'items_id'         => $task->getField($fk)
        ];
        $existing = $DB->request(
            'glpi_knowbaseitems_items',
            $params
        );
        if ($existing->numrows() == 0) {
            $kb_item_item = new KnowbaseItem_Item();
            $kb_item_item->add($params);
        }
    }

    if ($track->can($task->getField($fk), READ)) {
        $toadd = '';
       // Copy followup to KB redirect to KB
        if (isset($_POST['_task_to_kb']) && $_POST['_task_to_kb']) {
            $toadd = "&_task_to_kb=" . $task->getID();
        }
        $redirect = $track->getLinkURL() . $toadd;
    } else {
        Session::addMessageAfterRedirect(
            __('You have been redirected because you no longer have access to this ticket'),
            true,
            ERROR
        );
        $redirect = $track->getSearchURL();
    }
}

if (null == $redirect) {
    Html::back();
} else {
    Html::redirect($redirect);
}

Html::displayErrorAndDie('Lost');
