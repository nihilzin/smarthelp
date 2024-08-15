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

Session::checkLoginUser();

$item = new Item_Problem();

if (isset($_POST["add"])) {
    $item->check(-1, CREATE, $_POST);

    if ($item->add($_POST)) {
        Event::log(
            $_POST["problems_id"],
            "problem",
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
}

Html::displayErrorAndDie("lost");
