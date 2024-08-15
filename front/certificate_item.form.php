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

use Glpi\Event;

include('../inc/includes.php');

Session::checkCentralAccess();

$certif_item = new Certificate_Item();

if (isset($_POST["add"])) {
    $certif_item->check(-1, CREATE, $_POST);
    if ($certif_item->add($_POST)) {
        Event::log(
            $_POST["certificates_id"],
            "certificates",
            4,
            "certificate",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    foreach ($_POST["item"] as $key => $val) {
        $input = ['id' => $key];
        if ($val == 1) {
            $certif_item->check($key, UPDATE);
            $certif_item->delete($input);
        }
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
