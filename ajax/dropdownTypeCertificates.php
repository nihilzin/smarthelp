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

/** @var \DBmysql $DB */
global $DB;

if (strpos($_SERVER['PHP_SELF'], "dropdownTypeCertificates.php")) {
    $AJAX_INCLUDE = 1;
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
}


Session::checkCentralAccess();

// Make a select box
$used = [];

// Clean used array
if (
    isset($_POST['used'])
    && is_array($_POST['used'])
      && (count($_POST['used']) > 0)
) {
    foreach (
        $DB->request(
            'glpi_certificates',
            ['id'                  => $_POST['used'],
                'certificatetypes_id' => $_POST['certificatetype']
            ]
        ) as $data
    ) {
        $used[$data['id']] = $data['id'];
    }
}

Dropdown::show(
    'Certificate',
    ['name'      => $_POST['name'],
        'used'      => $used,
        'width'     => '50%',
        'entity'    => $_POST['entity'],
        'rand'      => $_POST['rand'],
    ]
);
