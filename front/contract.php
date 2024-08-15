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

Session::checkRight("contract", READ);

Html::header(Contract::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "management", "contract");

Search::show('Contract');

Html::footer();
