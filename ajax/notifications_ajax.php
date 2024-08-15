<?php

/**
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

include("../inc/includes.php");

Session::checkLoginUser();

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['delete'])) {
    NotificationAjax::raisedNotification($_GET['delete']);
    echo json_encode(true);
} else {
    $return = NotificationAjax::getMyNotifications();
    echo json_encode($return, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
