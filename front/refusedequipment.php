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

Session::checkRight("refusedequipment", READ);

Html::header(RefusedEquipment::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "admin", "glpi\inventory\inventory", "refusedequipment");

Search::show('RefusedEquipment');

Html::footer();
