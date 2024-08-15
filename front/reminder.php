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

Html::header(Reminder::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "reminder");

Search::show('Reminder');

Html::footer();
