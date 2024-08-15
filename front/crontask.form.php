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

Session::checkRight("config", UPDATE);

$crontask = new CronTask();

if (isset($_POST['execute'])) {
    if (is_numeric($_POST['execute'])) {
       // Execute button from list.
        $name = CronTask::launch(CronTask::MODE_INTERNAL, intval($_POST['execute']));
    } else {
       // Execute button from Task form (force)
        $name = CronTask::launch(-CronTask::MODE_INTERNAL, 1, $_POST['execute']);
    }
    if ($name) {
       //TRANS: %s is a task name
        Session::addMessageAfterRedirect(sprintf(__('Task %s executed'), $name));
    }
    Html::back();
} else if (isset($_POST["update"])) {
    Session::checkRight('config', UPDATE);
    $crontask->update($_POST);
    Html::back();
} else if (
    isset($_POST['resetdate'])
           && isset($_POST["id"])
) {
    Session::checkRight('config', UPDATE);
    if ($crontask->getFromDB($_POST["id"])) {
        $crontask->resetDate();
    }
    Html::back();
} else if (
    isset($_POST['resetstate'])
           && isset($_POST["id"])
) {
    Session::checkRight('config', UPDATE);
    if ($crontask->getFromDB($_POST["id"])) {
        $crontask->resetState();
    }
    Html::back();
} else {
    if (!isset($_GET["id"]) || empty($_GET["id"])) {
        exit();
    }
    $menus = ['config', 'crontask'];
    CronTask::displayFullPageForItem($_GET['id'], $menus);
}
