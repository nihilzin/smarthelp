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

Session::checkRight("domain", READ);

Html::header(Domain::getTypeName(1), $_SERVER['PHP_SELF'], "management", "domain");

Search::show('Domain');

Html::footer();
