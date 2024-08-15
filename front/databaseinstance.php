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

Session::checkRight('database', READ);

Html::header(DatabaseInstance::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "management", "database", "databaseinstance");

Search::show('DatabaseInstance');

Html::footer();
