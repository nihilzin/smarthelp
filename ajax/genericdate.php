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

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (isset($_POST['value']) && (strcmp($_POST['value'], '0') == 0)) {
    if ($_POST['withtime']) {
        Html::showDateTimeField($_POST['name'], ['value' => $_POST['specificvalue']]);
    } else {
        Html::showDateField($_POST['name'], ['value' => $_POST['specificvalue']]);
    }
} else {
    echo "<input type='hidden' name='" . $_POST['name'] . "' value='" . $_POST['value'] . "'>";
}
