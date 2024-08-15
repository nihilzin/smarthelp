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


$AJAX_INCLUDE = 1;
include('../inc/includes.php');

Html::header_nocache();
Session::checkLoginUser();

if (isset($_GET['get_raw']) && filter_var(($_GET['display_container'] ?? true), FILTER_VALIDATE_BOOLEAN)) {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($_SESSION['MESSAGE_AFTER_REDIRECT'] ?? []);
    $_SESSION['MESSAGE_AFTER_REDIRECT'] = [];
} else {
    // Send UTF8 Headers
    header("Content-Type: text/html; charset=UTF-8");
    Html::displayMessageAfterRedirect(filter_var(($_GET['display_container'] ?? true), FILTER_VALIDATE_BOOLEAN));
}
