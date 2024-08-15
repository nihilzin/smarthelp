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

Session::checkRight("consumable", READ);

$con      = new Consumable();
$constype = new ConsumableItem();

if (isset($_POST["add_several"])) {
    $constype->check($_POST["consumableitems_id"], UPDATE);

    for ($i = 0; $i < $_POST["to_add"]; $i++) {
        unset($con->fields["id"]);
        $con->add($_POST);
    }
    Event::log(
        $_POST["consumableitems_id"],
        "consumableitems",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s adds consumables'), $_SESSION["glpiname"])
    );

    Html::back();
} else {
    Html::back();
}
