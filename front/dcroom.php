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

Session::checkRight("datacenter", READ);

Html::header(DCRoom::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "management", "datacenter", "dcroom");

Search::show('DCRoom');

Html::footer();
