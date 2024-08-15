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

Session::checkRight("config", READ);

Html::header(
    NotImportedEmail::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "config",
    "mailcollector",
    "notimportedemails"
);

Search::show('NotImportedEmail');

Html::footer();
