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
if (strpos($_SERVER['PHP_SELF'], "planningcheck.php")) {
    $AJAX_INCLUDE = 1;
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
}

Session::checkLoginUser();

$append_params = [
    "checkavailability" => "checkavailability",
];

if (isset($_POST['users_id']) && ($_POST['users_id'] > 0)) {
    $append_params["itemtype"] = User::class;
    $append_params[User::getForeignKeyField()] = $_POST['users_id'];
} elseif (
    isset($_POST['parent_itemtype']) && class_exists($_POST['parent_itemtype'])
    && isset($_POST['parent_items_id']) && ($_POST['parent_items_id'] > 0)
    && isset($_POST['parent_fk_field']) && ($_POST['parent_fk_field'] != '')
) {
    $append_params["itemtype"] = $_POST['parent_itemtype'];
    $append_params[$_POST['parent_fk_field']] = $_POST['parent_items_id'];
}

if (count($append_params) > 1) {
    $rand = mt_rand();
    echo "<a href='#' title=\"" . __s('Availability') . "\" data-bs-toggle='modal' data-bs-target='#planningcheck$rand'>";
    echo "<i class='far fa-calendar-alt'></i>";
    echo "<span class='sr-only'>" . __('Availability') . "</span>";
    echo "</a>";
    Ajax::createIframeModalWindow(
        'planningcheck' . $rand,
        $CFG_GLPI["root_doc"] . "/front/planning.php?" . Toolbox::append_params($append_params),
        ['title'  => __('Availability')]
    );
}
