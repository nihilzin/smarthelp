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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

// Direct access to file
if (strpos($_SERVER['PHP_SELF'], "rulecriteria.php")) {
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
} else if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkLoginUser();

if (isset($_POST["sub_type"]) && ($rule = getItemForItemtype($_POST["sub_type"]))) {
    $criterias = $rule->getAllCriteria();

    if (count($criterias)) {
       // First include -> first of the predefined array
        if (!isset($_POST["criteria"])) {
            $_POST["criteria"] = key($criterias);
        }

        $allow_condition = $criterias[$_POST["criteria"]]['allow_condition'] ?? [];

        $condparam = ['criterion'        => $_POST["criteria"],
            'allow_conditions' => $allow_condition
        ];
        if (isset($_POST['condition'])) {
            $condparam['value'] = $_POST['condition'];
        }
        echo "<table class='w-100'><tr><td style='width: 30%'>";
        $randcrit = RuleCriteria::dropdownConditions($_POST["sub_type"], $condparam);
        echo "</td><td>";
        echo "<span id='condition_span$randcrit'>\n";
        echo "</span>\n";

        $paramscriteria = ['condition' => '__VALUE__',
            'criteria'  => $_POST["criteria"],
            'sub_type'  => $_POST["sub_type"]
        ];

        Ajax::updateItemOnSelectEvent(
            "dropdown_condition$randcrit",
            "condition_span$randcrit",
            $CFG_GLPI["root_doc"] . "/ajax/rulecriteriavalue.php",
            $paramscriteria
        );

        if (isset($_POST['pattern'])) {
            $paramscriteria['value'] = stripslashes($_POST['pattern']);
        }

        Ajax::updateItem(
            "condition_span$randcrit",
            $CFG_GLPI["root_doc"] . "/ajax/rulecriteriavalue.php",
            $paramscriteria,
            "dropdown_condition$randcrit"
        );
        echo "</td></tr></table>";
    }
}
