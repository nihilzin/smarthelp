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

Html::header(Rack::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", "rack");

Search::show('Rack');

Html::footer();
