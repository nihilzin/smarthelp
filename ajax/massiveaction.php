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

/**
 * @since 0.84
 */

use Glpi\Toolbox\Sanitizer;

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

try {
    $ma = new MassiveAction($_POST, $_GET, 'initial');
} catch (\Throwable $e) {
    echo "<div class='center'><img src='" . $CFG_GLPI["root_doc"] . "/pics/warning.png' alt='" .
                              __s('Warning') . "'><br><br>";
    echo "<span class='b'>" . $e->getMessage() . "</span><br>";
    echo "</div>";
    exit();
}

echo "<div class='center massiveactions'>";
Html::openMassiveActionsForm();
$params = ['action' => '__VALUE__'];
$input  = $ma->getInput();
foreach ($input as $key => $val) {
    // Value will be sanitized again when massive action form will be submitted.
    // It have to be unsanitized here to prevent double sanitization.
    $params[$key] = Sanitizer::unsanitize($val);
}

$actions = $params['actions'];

if (count($actions)) {
    if (isset($params['hidden']) && is_array($params['hidden'])) {
        foreach ($params['hidden'] as $key => $val) {
            echo Html::hidden($key, ['value' => $val]);
        }
    }
    echo _n('Action', 'Actions', 1);
    echo "&nbsp;";

    $actions = ['-1' => Dropdown::EMPTY_VALUE] + $actions;
    $rand    = Dropdown::showFromArray('massiveaction', $actions);

    echo "<br><br>";

    Ajax::updateItemOnSelectEvent(
        "dropdown_massiveaction$rand",
        "show_massiveaction$rand",
        $CFG_GLPI["root_doc"] . "/ajax/dropdownMassiveAction.php",
        $params
    );

    echo "<span id='show_massiveaction$rand'>&nbsp;</span>\n";
}

Html::closeForm();
echo "</div>";
