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

/**
 * @since 0.85
 */

include("../inc/includes.php");

Session::checkCentralAccess();
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else if (isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    $action = "import";
}

$rulecollection = new RuleCollection();
$rulecollection->checkGlobal(READ);

if ($action != "export") {
    Html::header(Rule::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "admin", "rule", -1);
}

switch ($action) {
    case "preview_import":
        $rulecollection->checkGlobal(UPDATE);
        if (RuleCollection::previewImportRules()) {
            break;
        }
        //seems wanted not to break; I do no understand why

    case "import":
        $rulecollection->checkGlobal(UPDATE);
        RuleCollection::displayImportRulesForm();
        break;

    case "export":
        $rule = new Rule();
        if (isset($_SESSION['exportitems'])) {
            $rules_key = array_keys($_SESSION['exportitems']);
        } else {
            $rules_key = array_keys($rule->find(getEntitiesRestrictCriteria()));
        }
        $rulecollection->exportRulesToXML($rules_key);
        unset($_SESSION['exportitems']);
        break;

    case "download":
        echo "<div class='center'>";
        $itemtype = $_REQUEST['itemtype'];
        echo "<a href='" . $itemtype::getSearchURL() . "'>" . __('Back') . "</a>";
        echo "</div>";
        Html::redirect("rule.backup.php?action=export&itemtype=" . $_REQUEST['itemtype']);
        break;

    case "process_import":
        $rulecollection->checkGlobal(UPDATE);
        RuleCollection::processImportRules();
        Html::back();
        break;
}
if ($action != "export") {
    Html::footer();
}
