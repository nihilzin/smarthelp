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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

Session::checkSeveralRightsOr(['notification' => READ,
    'config'       => UPDATE
]);

Html::header(_n('Notification', 'Notifications', Session::getPluralNumber()), $_SERVER['PHP_SELF'], "config", "notification");

if (
    !Session::haveRight("config", READ)
    && Session::haveRight("notification", READ)
) {
    Html::redirect($CFG_GLPI["root_doc"] . '/front/notification.php');
}

$settingconfig = new NotificationSettingConfig();

// Init $CFG_GLPI['notifications_modes']
Notification_NotificationTemplate::getModes();

if (count($_POST)) {
    $settingconfig->update($_POST);
    Html::back();
}

$settingconfig->showConfigForm();

Html::footer();
