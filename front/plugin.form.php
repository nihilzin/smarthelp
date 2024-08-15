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

$plugin = new Plugin();

$id     = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : null;
$action = $id > 0 && isset($_POST['action']) ? $_POST['action'] : null;

switch ($action) {
    case 'install':
        $plugin->install($id);
        break;
    case 'activate':
        $plugin->activate($id);
        break;
    case 'unactivate':
        $plugin->unactivate($id);
        break;
    case 'uninstall':
        $plugin->uninstall($id);
        break;
    case 'clean':
        $plugin->clean($id);
        break;
    default:
        Html::displayErrorAndDie('Lost');
        break;
}

Html::back();
