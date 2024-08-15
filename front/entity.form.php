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

include('../inc/includes.php');

/**
 * @var \DBmysql $DB
 * @var array $_UPOST
 */
global $DB, $_UPOST;

$dropdown = new Entity();

// Root entity : no delete
if (isset($_GET['id']) && ($_GET['id'] == 0)) {
    $options = ['canedit' => true,
        'candel'  => false
    ];
}

if (array_key_exists('custom_css_code', $_POST)) {
    // Prevent sanitize process to alter '<', '>' and '&' chars.
    $_POST['custom_css_code'] = $DB->escape($_UPOST['custom_css_code']);
}

include(GLPI_ROOT . "/front/dropdown.common.form.php");
