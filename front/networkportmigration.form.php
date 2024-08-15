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

/**
 * @var array $CFG_GLPI
 * @var \DBmysql $DB
 */
global $CFG_GLPI, $DB;

include('../inc/includes.php');

if (!$DB->tableExists('glpi_networkportmigrations')) {
    Html::displayNotFoundError();
}

$np = new NetworkPortMigration();

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

if (isset($_POST["purge"])) {
    $np->check($_POST['id'], PURGE);
    $np->delete($_POST, 1);
    Event::log(
        $_POST['id'],
        "networkport",
        5,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );

    Html::redirect($CFG_GLPI["root_doc"] . "/front/networkportmigration.php");
} else if (isset($_POST["delete_several"])) {
    Session::checkRight("networking", UPDATE);

    if (isset($_POST["del_port"]) && count($_POST["del_port"])) {
        foreach ($_POST["del_port"] as $port_id => $val) {
            if ($np->can($port_id, PURGE)) {
                $np->delete(["id" => $port_id]);
            }
        }
    }
    Event::log(
        0,
        "networkport",
        5,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes several network ports'), $_SESSION["glpiname"])
    );

    Html::back();
} else if (isset($_POST["update"])) {
    $np->check($_POST['id'], PURGE);

    $networkport = new NetworkPort();
    if ($networkport->can($_POST['id'], UPDATE)) {
        if ($networkport->switchInstantiationType($_POST['transform_to']) !== false) {
            $instantiation             = $networkport->getInstantiation();
            $input                     = $np->fields;
            $input['networkports_id']  = $input['id'];
            unset($input['id']);
            if ($instantiation->add($input)) {
                $np->delete($_POST);
            }
        } else {
            Session::addMessageAfterRedirect(__('Cannot change a migration network port to an unknown one'));
        }
    } else {
        Session::addMessageAfterRedirect(__('Network port is not available...'));
        $np->delete($_POST);
    }

    Html::redirect($CFG_GLPI["root_doc"] . "/front/networkportmigration.php");
} else {
    $menus = ["tools", "migration", "networkportmigration"];
    NetworkPort::displayFullPageForItem($_GET["id"], $menus);
}
