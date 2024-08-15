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

Session::checkRight("locked_field", CREATE);

Html::header(Lockedfield::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "admin", "glpi\inventory\inventory", "lockedfield");

Search::show('Lockedfield');

Html::footer();
