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

if (strpos($_SERVER['PHP_SELF'], "dropdownRubDocument.php")) {
    $AJAX_INCLUDE = 1;
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
}

Session::checkCentralAccess();

// Make a select box
if (isset($_POST["rubdoc"])) {
    $used = [];

   // Clean used array
    if (isset($_POST['used']) && is_array($_POST['used']) && (count($_POST['used']) > 0)) {
        $iterator = $DB->request([
            'SELECT' => ['id'],
            'FROM'   => 'glpi_documents',
            'WHERE'  => [
                'id'                    => $_POST['used'],
                'documentcategories_id' => (int)$_POST['rubdoc']
            ]
        ]);

        foreach ($iterator as $data) {
            $used[$data['id']] = $data['id'];
        }
    }

    if (preg_match('/[^a-z_\-0-9]/i', $_POST['myname'])) {
        throw new \RuntimeException('Invalid name provided!');
    }

    if (!isset($_POST['entity']) || $_POST['entity'] === '') {
        $_POST['entity'] = $_SESSION['glpiactive_entity'];
    }

    Dropdown::show(
        'Document',
        [
            'name'      => $_POST['myname'],
            'used'      => $used,
            'width'     => '50%',
            'entity'    => intval($_POST['entity']),
            'rand'      => intval($_POST['rand']),
            'condition' => ['glpi_documents.documentcategories_id' => (int)$_POST["rubdoc"]]
        ]
    );
}
