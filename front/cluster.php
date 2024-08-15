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

Session::checkRight("cluster", READ);

Html::header(Cluster::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "management", "cluster");

Search::show('Cluster');

Html::footer();
