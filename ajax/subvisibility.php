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
if (strpos($_SERVER['PHP_SELF'], "subvisibility.php")) {
    $AJAX_INCLUDE = 1;
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
}

Session::checkLoginUser();

if (
    isset($_POST['type']) && !empty($_POST['type'])
    && isset($_POST['items_id']) && ($_POST['items_id'] > 0)
) {
    $prefix = '';
    $suffix = '';
    if (isset($_POST['prefix']) && !empty($_POST['prefix'])) {
        $prefix = $_POST['prefix'] . '[';
        $suffix = ']';
    }

    switch ($_POST['type']) {
        case 'Group':
        case 'Profile':
            $params = ['value' => $_SESSION['glpiactive_entity'],
                'name'  => $prefix . 'entities_id' . $suffix
            ];
            if (Session::canViewAllEntities()) {
                $params['toadd'] = [-1 => __('No restriction')];
            }
            echo "<table class='tab_format'><tr><td>";
            echo Entity::getTypeName(1);
            echo "</td><td>";
            Entity::dropdown($params);
            echo "</td><td>";
            echo __('Child entities');
            echo "</td><td>";
            Dropdown::showYesNo($prefix . 'is_recursive' . $suffix);
            echo "</td></tr></table>";
            break;
    }
}
