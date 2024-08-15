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

/**
 * @since 0.85
 */
include('../inc/includes.php');

$rule = new Rule();
$rule->getFromDB(intval($_POST['rules_id']));

$action = new RuleAction($rule->fields['sub_type']);

if (isset($_POST["add"])) {
    $action->check(-1, CREATE, $_POST);
    $action->add($_POST);

    Html::back();
} else if (isset($_POST["update"])) {
    $action->check($_POST['id'], UPDATE);
    $action->update($_POST);

    Html::back();
} else if (isset($_POST["purge"])) {
    $action->check($_POST['id'], PURGE);
    $action->delete($_POST, 1);

    Html::back();
}
