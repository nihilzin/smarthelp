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
 * @since 0.84
 */

use Glpi\Event;

include('../inc/includes.php');

$cipm = new CartridgeItem_PrinterModel();
if (isset($_POST["add"])) {
    $cipm->check(-1, CREATE, $_POST);
    if ($cipm->add($_POST)) {
        Event::log(
            $_POST["cartridgeitems_id"],
            "cartridges",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s associates a type'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}
Html::displayErrorAndDie('Lost');
