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

Session::checkRight("certificate", READ);

Html::header(
    Certificate::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    'management',
    'certificate'
);

Search::show('Certificate');

Html::footer();
