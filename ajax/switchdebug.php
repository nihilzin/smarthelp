<?php

/**
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

if (Config::canUpdate()) {
    $mode = ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE ? Session::NORMAL_MODE : Session::DEBUG_MODE);
    $user = new User();
    $user->update(
        [
            'id'        => Session::getLoginUserID(),
            'use_mode'  => $mode
        ]
    );
    Session::addMessageAfterRedirect(
        $_SESSION['glpi_use_mode'] == Session::DEBUG_MODE ?
         __('Debug mode has been enabled!') :
         __('Debug mode has been disabled!')
    );
}

Html::back();
