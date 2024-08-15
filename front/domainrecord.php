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

Session::checkCentralAccess();

Html::header(Domain::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "management", "domain", "domainrecord");

Search::show('DomainRecord');

Html::footer();
