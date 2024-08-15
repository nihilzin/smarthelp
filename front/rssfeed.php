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

/**
 * @since 0.84
 */

include('../inc/includes.php');

Session::checkCentralAccess();

Html::header(RSSFeed::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "rssfeed");

Search::show('RSSFeed');

Html::footer();
