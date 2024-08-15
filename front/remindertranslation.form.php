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

$translation = new ReminderTranslation();

if (isset($_POST['add'])) {
    $translation->add($_POST);
    Html::back();
} else if (isset($_POST['update'])) {
    $translation->update($_POST);
    Html::back();
} else if (isset($_POST["purge"])) {
    $translation->delete($_POST, true);
    Html::redirect(Reminder::getFormURLWithID($_POST['reminders_id']));
} else if (isset($_GET["id"])) {
    $menus = ["tools", "remindertranslation"];
    ReminderTranslation::displayFullPageForItem($_GET['id'], $menus);
}
