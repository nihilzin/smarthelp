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

Session::checkCentralAccess();

if (!isset($_POST["itemtype"]) || !($item = getItemForItemtype($_POST['itemtype']))) {
    exit();
}

$item::dropdown();
echo "<br/><input type='submit' name='update' value=\"" . _sx('button', 'Update') . "\" class='btn btn-primary'>";
echo "<br/><input type='submit' name='clone' value=\"" . _sx('button', 'Clone') . "\" class='btn btn-primary'>";
