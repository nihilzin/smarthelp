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

Session::checkRight('appliance', READ);

Html::header(Appliance::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "management", "appliance");

Search::show('Appliance');

Html::footer();
