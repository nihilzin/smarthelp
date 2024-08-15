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

Session::checkRight(Event::$rightname, READ);

Html::header(Event::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "admin", "Glpi\\Event");

Event::showList($_SERVER['PHP_SELF'], $_GET['order'] ?? 'DESC', $_GET['sort']  ?? 'date', $_GET['start'] ?? 0);

Html::footer();
