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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include("../inc/includes.php");

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
$client = new APIClient();

if (isset($_POST["add"])) {
    $client->check(-1, CREATE, $_POST);

    if ($newID = $client->add($_POST)) {
        Event::log(
            $newID,
            APIClient::class,
            4,
            "setup",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($client->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["update"])) {
    $client->check($_POST["id"], UPDATE);
    $client->update($_POST);
    Event::log(
        $_POST["id"],
        APIClient::class,
        4,
        "setup",
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_POST["purge"])) {
    $client->check($_POST["id"], PURGE);
    $client->delete($_POST);
    Event::log(
        $_POST["id"],
        APIClient::class,
        4,
        "setup",
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    Html::redirect($CFG_GLPI["root_doc"] . "/front/config.form.php");
} else {
    $menus = ["config", "config", "apiclient"];
    APIClient::displayFullPageForItem($_GET["id"], $menus);
}
