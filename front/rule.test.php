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

Session::checkCentralAccess();

if (isset($_POST["sub_type"])) {
    $sub_type = $_POST["sub_type"];
} else if (isset($_GET["sub_type"])) {
    $sub_type = $_GET["sub_type"];
} else {
    $sub_type = 0;
}

if (isset($_POST["rules_id"])) {
    $rules_id = $_POST["rules_id"];
} else if (isset($_GET["rules_id"])) {
    $rules_id = $_GET["rules_id"];
} else {
    $rules_id = 0;
}

if (!$rule = getItemForItemtype($sub_type)) {
    exit;
}
$rule->checkGlobal(READ);

Html::popHeader(__('Setup'), $_SERVER['PHP_SELF']);

$rule->showRulePreviewCriteriasForm($_SERVER['PHP_SELF'], $rules_id);

if (isset($_POST["test_rule"])) {
    $params = [];
   //Unset values that must not be processed by the rule
    unset($_POST["test_rule"]);
    unset($_POST["rules_id"]);
    unset($_POST["sub_type"]);
    $rule->getRuleWithCriteriasAndActions($rules_id, 1, 1);

   // Need for RuleEngines
    foreach ($_POST as $key => $val) {
        $_POST[$key] = stripslashes($val);
    }
   //Add rules specific POST fields to the param array
    $params = $rule->addSpecificParamsForPreview($params);

    $input = $rule->prepareAllInputDataForProcess($_POST, $params);
   //$rule->regex_results = array();
    echo "<br>";
    $rule->showRulePreviewResultsForm($_SERVER['PHP_SELF'], $input, $params);
}

Html::popFooter();
