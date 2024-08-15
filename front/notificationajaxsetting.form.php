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

use Glpi\Event;

include('../inc/includes.php');

Session::checkRight("config", UPDATE);
$notificationajax = new NotificationAjaxSetting();

if (!empty($_POST["test_ajax_send"])) {
    NotificationAjax::testNotification();
    Html::back();
} else if (!empty($_POST["update"])) {
    $config = new Config();
    $config->update($_POST);
    Event::log(0, "system", 3, "setup", sprintf(
        __('%1$s edited the browsers notifications configuration'),
        $_SESSION["glpiname"] ?? __("Unknown"),
    ));
    Html::back();
}

$menus = ["config", "notification", "config"];
$config_id = Config::getConfigIDForContext('core');
NotificationAjaxSetting::displayFullPageForItem($config_id, $menus);
