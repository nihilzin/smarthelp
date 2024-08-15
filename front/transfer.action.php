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

Html::header(__('Transfer'), '', 'admin', 'rule', 'transfer');

$transfer = new Transfer();

$transfer->checkGlobal(READ);

if (isset($_POST['transfer'])) {
    if (isset($_SESSION['glpitransfer_list'])) {
        if (!Session::haveAccessToEntity($_POST['to_entity'])) {
            Html::displayRightError();
        }
        $transfer->moveItems($_SESSION['glpitransfer_list'], $_POST['to_entity'], $_POST);
        unset($_SESSION['glpitransfer_list']);
        echo "<div class='b center'>" . __('Operation successful') . "<br>";
        echo "<a href='central.php'>" . __('Back') . "</a></div>";
        Html::footer();
        exit();
    }
} else if (isset($_POST['clear'])) {
    unset($_SESSION['glpitransfer_list']);
    echo "<div class='b center'>" . __('Operation successful') . "<br>";
    echo "<a href='central.php'>" . __('Back') . "</a></div>";
    echo "</div>";
    Html::footer();
    exit();
}

unset($_SESSION['glpimassiveactionselected']);

$transfer->showTransferList();

Html::footer();
