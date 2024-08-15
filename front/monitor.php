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

Session::checkRight("monitor", READ);

Html::header(Monitor::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", "monitor");

Search::show('Monitor');

Html::footer();
