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

/** @var array $_UREQUEST */
global $_UREQUEST;

$AJAX_INCLUDE = 1;
include('../inc/includes.php');

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

$mailcollector = new MailCollector();

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case "getFoldersList":
           // Load config if already exists
           // Necessary if password is not updated
            if (array_key_exists('id', $_REQUEST)) {
                $mailcollector->getFromDB($_REQUEST['id']);
            }

           // Update fields with input values
            $input = $_REQUEST;
            if (array_key_exists('passwd', $input)) {
               // Password must not be altered, it will be encrypted and never displayed, so sanitize is not necessary.
                $input['passwd'] = $_UREQUEST['passwd'];
            }
            $input['login'] = stripslashes($input['login']);

            if (isset($input["passwd"])) {
                if (empty($input["passwd"])) {
                    unset($input["passwd"]);
                } else {
                    $input["passwd"] = (new GLPIKey())->encrypt($input["passwd"]);
                }
            }

            if (isset($input['mail_server']) && !empty($input['mail_server'])) {
                $input["host"] = Toolbox::constructMailServerConfig($input);
            }

            if (!isset($input['errors'])) {
                $input['errors'] = 0;
            }

            $mailcollector->fields = array_merge($mailcollector->fields, $input);
            $mailcollector->displayFoldersList($_REQUEST['input_id']);
            break;
    }
}
