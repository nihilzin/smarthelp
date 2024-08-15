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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

if (!isset($_GET['itemtype']) || !class_exists($_GET['itemtype'])) {
    throw new \RuntimeException(
        'Missing or incorrect item device type called!'
    );
}

$itemDevice = new $_GET['itemtype']();
if (!$itemDevice->canView()) {
    Session::redirectIfNotLoggedIn();
    Html::displayRightError();
}

if (in_array($itemDevice->getType(), $CFG_GLPI['devices_in_menu'])) {
    Html::header($itemDevice->getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", strtolower($itemDevice->getType()));
} else {
    Html::header($itemDevice->getTypeName(Session::getPluralNumber()), '', "config", "commondevice", $itemDevice->getType());
}

Search::show($_GET['itemtype']);

Html::footer();
