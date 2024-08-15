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

use Glpi\Mail\SMTP\OauthConfig;

/** @var array $CFG_GLPI */
global $CFG_GLPI;

if (!array_key_exists('cookie_refresh', $_GET)) {
    // Session cookie will not be accessible when user will be redirected from provider website
    // if `session.cookie_samesite` configuration value is `strict`.
    // Redirecting on self using `http-equiv="refresh"` will get around this limitation.
    $url = htmlspecialchars(
        $_SERVER['REQUEST_URI']
        . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?')
        . 'cookie_refresh'
    );

    echo <<<HTML
<html>
<head>
    <meta http-equiv="refresh" content="0;URL='{$url}'"/>
</head>
    <body></body>
</html>
HTML;
    exit;
}

include('../inc/includes.php');

Session::checkRight("config", UPDATE);

if (
    (array_key_exists('error', $_GET) && $_GET['error'] !== '')
    || (array_key_exists('error_description', $_GET) && $_GET['error_description'] !== '')
) {
    // Got an error, probably user denied access
    Session::addMessageAfterRedirect(
        sprintf(_x('oauth', 'Authorization failed with error: %s'), $_GET['error_description'] ?? $_GET['error']),
        false,
        ERROR
    );
} elseif (
    !array_key_exists('state', $_GET)
    || !array_key_exists('smtp_oauth2_state', $_SESSION)
    || $_GET['state'] !== $_SESSION['smtp_oauth2_state']
) {
    Session::addMessageAfterRedirect(_x('oauth', 'Unable to verify authorization code'), false, ERROR);
} elseif (!array_key_exists('code', $_GET)) {
    Session::addMessageAfterRedirect(_x('oauth', 'Unable to get authorization code'), false, ERROR);
} else {
    $provider = OauthConfig::getInstance()->getSmtpOauthProvider();

    if ($provider !== null) {
        $code = $_GET['code'];
        try {
            $token         = $provider->getAccessToken('authorization_code', ['code'  => $code]);
            $refresh_token = $token->getRefreshToken();
            $email         = $provider->getResourceOwner($token)->toArray()['email'] ?? null;

            $is_email_valid = !empty($email);
            if (!$is_email_valid) {
                Session::addMessageAfterRedirect(
                    _x('oauth', 'Access token does not provide an email address, please verify token claims configuration.'),
                    false,
                    ERROR
                );
            }

            $is_token_valid = !empty($refresh_token);
            if (!$is_token_valid) {
                Session::addMessageAfterRedirect(
                    _x('oauth', 'Access token does not provide a refresh token, please verify application configuration.'),
                    false,
                    ERROR
                );
            }

            if ($is_email_valid && $is_token_valid) {
                Config::setConfigurationValues(
                    'core',
                    [
                        'smtp_username'            => $email,
                        'smtp_oauth_refresh_token' => $refresh_token,
                    ]
                );
            }
        } catch (\Throwable $e) {
            trigger_error(
                sprintf('Error during authorization code fetching: %s', $e->getMessage()),
                E_USER_WARNING
            );
            Session::addMessageAfterRedirect(
                sprintf(_x('oauth', 'Unable to fetch authorization code. Error is: %s'), $e->getMessage()),
                false,
                ERROR
            );
        }
    } else {
        Session::addMessageAfterRedirect(_x('oauth', 'Invalid provider configuration'), false, ERROR);
    }
}

Html::redirect($CFG_GLPI['root_doc'] . '/front/notificationmailingsetting.form.php');
