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

Session::checkRight("internet", READ);

Html::header(
    NetworkName::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    'config',
    'commondropdown',
    'NetworkName'
);

Search::show('NetworkName');

Html::footer();
