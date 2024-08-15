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
    NotificationTemplate::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "config",
    "notification",
    "notificationtemplate"
);

Search::show('NotificationTemplate');

Html::footer();
