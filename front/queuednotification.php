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

Session::checkRight("queuednotification", READ);

Html::header(QueuedNotification::getTypeName(), $_SERVER['PHP_SELF'], "admin", "queuednotification");

Search::show('QueuedNotification');

Html::footer();
