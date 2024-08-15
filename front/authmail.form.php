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

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$config_mail = new AuthMail();

//IMAP/POP Server add/update/delete
if (isset($_POST["update"])) {
    $config_mail->update($_POST);
    Html::back();
} else if (isset($_POST["add"])) {
   //If no name has been given to this configuration, then go back to the page without adding
    if ($_POST["name"] != "") {
        if (
            ($config_mail->add($_POST))
            && $_SESSION['glpibackcreated']
        ) {
            Html::redirect($config_mail->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $config_mail->delete($_POST, 1);
    $_SESSION['glpi_authconfig'] = 2;
    $config_mail->redirectToList();
} else if (isset($_POST["test"])) {
    if (AuthMail::testAuth($_POST["imap_string"], $_POST["imap_login"], $_POST["imap_password"])) {
        Session::addMessageAfterRedirect(__('Test successful'));
    } else {
        Session::addMessageAfterRedirect(__('Test failed'), false, ERROR);
    }
    Html::back();
}

$menus = ["config", "auth", "imap"];
AuthMail::displayFullPageForItem($_GET['id'], $menus);
