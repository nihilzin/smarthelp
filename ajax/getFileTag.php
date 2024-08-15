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

header('Content-type: application/json');
Html::header_nocache();

Session::checkLoginUser();

if (isset($_POST['data'])) {
    $response = [];

    foreach (array_keys($_POST['data']) as $key) {
        $unique_name = Rule::getUuid();
        $response[$key] = ['tag' => Document::getImageTag($unique_name), 'name' => $unique_name];
    }

    echo json_encode($response);
}
