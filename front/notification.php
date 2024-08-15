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

Session::checkRight("notification", READ);

Html::header(
    Notification::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "config",
    "notification",
    "notification"
);

Search::show('Notification');

Html::footer();
