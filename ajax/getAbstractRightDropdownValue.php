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

include(__DIR__ . '/../inc/includes.php');

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();
Session::checkLoginUser();

function show_rights_dropdown(string $class)
{
    $search = $_POST['searchText'] ?? "";
    echo json_encode($class::fetchValues($search));
}
