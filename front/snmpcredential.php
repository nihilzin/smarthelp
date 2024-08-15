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

Session::checkRight("snmpcredential", READ);

Html::header(SNMPCredential::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "admin", "glpi\inventory\inventory", "snmpcredential");

Search::show('SNMPCredential');

Html::footer();
