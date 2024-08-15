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

include('../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkCentralAccess();

// Make a select box
if ($_POST["idtable"] && class_exists($_POST["idtable"])) {
   // Link to user for search only > normal users
    $link = "getDropdownValue.php";

    if ($_POST["idtable"] == 'User') {
        $link = "getDropdownUsers.php";
    }

    $rand = $_POST['rand'] ?? mt_rand();

    $field_id = Html::cleanId("dropdown_" . $_POST["name"] . $rand);

    $p        = [
        'value'               => 0,
        'valuename'           => Dropdown::EMPTY_VALUE,
        'itemtype'            => $_POST["idtable"],
        'display_emptychoice' => true,
        'displaywith'         => ['otherserial', 'serial'],
        '_idor_token'         => Session::getNewIDORToken($_POST["idtable"]),
    ];
    if (isset($_POST['value'])) {
        $p['value'] = $_POST['value'];
    }
    if (isset($_POST['entity_restrict'])) {
        $p['entity_restrict'] = $_POST['entity_restrict'];
    }
    if (isset($_POST['condition'])) {
        $p['condition'] = $_POST['condition'];
    }
    if (isset($_POST['used'])) {
        $_POST['used'] = Toolbox::jsonDecode($_POST['used'], true);
    }
    if (isset($_POST['used'][$_POST['idtable']])) {
        $p['used'] = $_POST['used'][$_POST['idtable']];
    }
    if (isset($_POST['width'])) {
        $p['width'] = $_POST['width'];
    }

    echo  Html::jsAjaxDropdown(
        $_POST["name"],
        $field_id,
        $CFG_GLPI['root_doc'] . "/ajax/" . $link,
        $p
    );

    if (!empty($_POST['showItemSpecificity'])) {
        $params = ['items_id' => '__VALUE__',
            'itemtype' => $_POST["idtable"]
        ];
        if (isset($_POST['entity_restrict'])) {
            $params['entity_restrict'] = $_POST['entity_restrict'];
        }

        Ajax::updateItemOnSelectEvent(
            $field_id,
            "showItemSpecificity_" . $_POST["name"] . "$rand",
            $_POST['showItemSpecificity'],
            $params
        );

        echo "<br><span id='showItemSpecificity_" . $_POST["name"] . "$rand'>&nbsp;</span>\n";
    }
}
