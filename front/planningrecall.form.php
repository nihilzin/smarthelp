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

Session::checkLoginUser();

$pr = new PlanningRecall();

if (isset($_POST["update"])) {
    $pr->manageDatas($_POST['_planningrecall']);
    Html::back();
}

Html::displayErrorAndDie("lost");
