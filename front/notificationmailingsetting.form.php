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

use Glpi\Application\ErrorHandler;
use Glpi\Event;
use Glpi\Mail\SMTP\OauthConfig;
use Glpi\Toolbox\Sanitizer;

include('../inc/includes.php');

Session::checkRight("config", UPDATE);

if (isset($_POST["test_smtp_send"])) {
    NotificationMailing::testNotification();
    Html::back();
} else if (isset($_POST["update"])) {
    if (array_key_exists('smtp_passwd', $_POST)) {
        // Password must not be altered, it will be encrypted and never displayed, so sanitize is not necessary.
        $_POST['smtp_passwd'] = Sanitizer::unsanitize($_POST['smtp_passwd']);
    }
    $config = new Config();
    $config->update($_POST);
    Event::log(0, "system", 3, "setup", sprintf(
        __('%1$s edited the emails notifications configuration'),
        $_SESSION["glpiname"] ?? __("Unknown"),
    ));

    $redirect_to_smtp_oauth = $_SESSION['redirect_to_smtp_oauth'] ?? false;
    unset($_SESSION['redirect_to_smtp_oauth']);
    if ($redirect_to_smtp_oauth) {
        $provider = OauthConfig::getInstance()->getSmtpOauthProvider();

        if ($provider !== null) {
            try {
                $auth_url = $provider->getAuthorizationUrl();
                $_SESSION['smtp_oauth2_state'] = $provider->getState();
                Html::redirect($auth_url);
            } catch (\Throwable $e) {
                ErrorHandler::getInstance()->handleException($e, true);
                Session::addMessageAfterRedirect(
                    sprintf(_x('oauth', 'Authorization failed with error: %s'), $e->getMessage()),
                    false,
                    ERROR
                );
                Html::back();
            }
        }
    }

    Html::back();
}

$menus = ["config", "notification", "config"];
$config_id = Config::getConfigIDForContext('core');
NotificationMailingSetting::displayFullPageForItem($config_id, $menus);
