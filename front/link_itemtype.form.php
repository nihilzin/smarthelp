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

$link          = new Link();
$link_itemtype = new Link_Itemtype();

if (isset($_POST["add"])) {
    $link->check(-1, CREATE, $_POST);

    if ($link_itemtype->add($_POST)) {
        Event::log(
            $_POST["links_id"],
            "links",
            4,
            "setup",
            //TRANS: %s is the user login
            sprintf(__('%s adds a link with an item'), $_SESSION["glpiname"])
        );
    }
    Html::redirect($link->getFormURLWithID($_POST["links_id"]));
}
