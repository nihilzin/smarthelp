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

Session::checkRight("license", READ);

Html::header(
    SoftwareLicense::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "management",
    "softwarelicense"
);

Search::show('SoftwareLicense');

Html::footer();
