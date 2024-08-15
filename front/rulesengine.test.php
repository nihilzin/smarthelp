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

if (isset($_POST["condition"])) {
    $condition = $_POST["condition"];
} else if (isset($_GET["condition"])) {
    $condition = $_GET["condition"];
} else {
    $condition = 0;
}

if (isset($_GET['refusedequipments_id'])) {
    $_POST['refusedequipments_id'] = $_GET['refusedequipments_id'];
}

$rulecollection = RuleCollection::getClassByType($sub_type);
if ($rulecollection->isRuleRecursive()) {
    $rulecollection->setEntity($_SESSION['glpiactive_entity']);
}
$rulecollection->checkGlobal(READ);

Html::popHeader(__('Setup'), $_SERVER['PHP_SELF']);

// Need for RuleEngines
foreach ($_POST as $key => $val) {
    $_POST[$key] = stripslashes($val);
}
$rulecollection->showRulesEnginePreviewCriteriasForm($_SERVER['PHP_SELF'], $_POST, $condition);

if (isset($_POST["test_all_rules"])) {
   //Unset values that must not be processed by the rule
    unset($_POST["sub_type"]);
    unset($_POST["test_all_rules"]);

    echo "<br>";
    $rulecollection->showRulesEnginePreviewResultsForm($_SERVER['PHP_SELF'], $_POST, $condition);
}

Html::popFooter();
