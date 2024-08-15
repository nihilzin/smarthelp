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


$criteria = new SlaLevelCriteria();

if (isset($_POST["add"])) {
    $criteria->check(-1, CREATE, $_POST);
    $criteria->add($_POST);

    Html::back();
} else if (isset($_POST["update"])) {
    $criteria->check($_POST['id'], UPDATE);
    $criteria->update($_POST);

    Html::back();
} else if (isset($_POST["purge"])) {
    $criteria->check($_POST['id'], PURGE);
    $criteria->delete($_POST, 1);

    Html::back();
}
