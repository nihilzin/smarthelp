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

Session::checkRight("cable_management", READ);

Html::header(Cable::getTypeName(1), $_SERVER['PHP_SELF'], "assets", "cable");

Search::show('Cable');

Html::footer();
