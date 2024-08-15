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

if (!defined('GLPI_ROOT')) {
    include('../inc/includes.php');
}

Html::popHeader(__('List of available tags'), $_SERVER['PHP_SELF']);

if (isset($_GET["sub_type"])) {
    Session::checkCentralAccess();
    NotificationTemplateTranslation::showAvailableTags($_GET["sub_type"]);
    Html::ajaxFooter();
} else {
    Html::displayErrorAndDie("lost");
}

Html::popFooter();
