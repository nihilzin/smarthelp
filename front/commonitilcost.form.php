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
 * @var CommonITILCost $cost
 */

use Glpi\Event;

// autoload include in objecttask.form (ticketcost, problemcost,...)
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkCentralAccess();
if (!($cost instanceof CommonITILCost)) {
    Html::displayErrorAndDie('');
}
if (!$cost->canView()) {
    Html::displayRightError();
}
$itemtype = $cost->getItilObjectItemType();
$fk       = getForeignKeyFieldForItemType($itemtype);


if (isset($_POST["add"])) {
    $cost->check(-1, CREATE, $_POST);

    if ($cost->add($_POST)) {
        Event::log(
            $_POST[$fk],
            strtolower($itemtype),
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s adds a cost'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $cost->check($_POST["id"], PURGE);
    if ($cost->delete($_POST, 1)) {
        Event::log(
            $cost->fields[$fk],
            strtolower($itemtype),
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s purges a cost'), $_SESSION["glpiname"])
        );
    }
    Html::redirect($itemtype::getFormURLWithID($cost->fields[$fk]));
} else if (isset($_POST["update"])) {
    $cost->check($_POST["id"], UPDATE);

    if ($cost->update($_POST)) {
        Event::log(
            $cost->fields[$fk],
            strtolower($itemtype),
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s updates a cost'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

Html::displayErrorAndDie('Lost');
