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

// Direct access to file
if (strstr($_SERVER['PHP_SELF'], "rulecriteriavalue.php")) {
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
} else if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkLoginUser();

// Non define case
if (isset($_POST["sub_type"]) && ($rule = getItemForItemtype($_POST["sub_type"]))) {
    $value = '';
    if (isset($_POST['value'])) {
        $value = stripslashes($_POST['value']);
    }
    $rule->displayCriteriaSelectPattern("pattern", $_POST["criteria"], $_POST['condition'], $value);
}
