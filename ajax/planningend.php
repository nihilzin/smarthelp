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

$AJAX_INCLUDE = 1;
include('../inc/includes.php');

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (
    isset($_POST['duration']) && ($_POST['duration'] == 0)
    && isset($_POST['name'])
) {
    if (!isset($_POST['global_begin'])) {
        $_POST['global_begin'] = '';
    }
    if (!isset($_POST['global_end'])) {
        $_POST['global_end'] = '';
    }
    Html::showDateTimeField($_POST['name'], ['value'      =>  $_POST['end'],
        'timestep'   => -1,
        'maybeempty' => false,
        'canedit'    => true,
        'mindate'    => '',
        'maxdate'    => '',
        'mintime'    => $_POST['global_begin'],
        'maxtime'    => $_POST['global_end']
    ]);
}
