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

include('../inc/includes.php');
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (!isset($_POST['type'])) {
    exit();
}
if (!isset($_POST['parenttype'])) {
    exit();
}

if (
    ($item = getItemForItemtype($_POST['type']))
    && ($parent = getItemForItemtype($_POST['parenttype']))
) {
    if (
        isset($_POST[$parent->getForeignKeyField()])
        && isset($_POST["id"])
        && $parent->getFromDB($_POST[$parent->getForeignKeyField()])
    ) {
        $item->showForm($_POST["id"], ['parent' => $parent]);
    } else {
        echo __('Access denied');
    }
}

Html::ajaxFooter();
