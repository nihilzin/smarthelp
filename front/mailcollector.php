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

Session::checkRight("config", UPDATE);

Html::header(MailCollector::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "config", "mailcollector");

$mailcollector = new MailCollector();
$mailcollector->title();
Search::show('MailCollector');
Html::footer();
