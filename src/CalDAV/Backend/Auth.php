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

namespace Glpi\CalDAV\Backend;

use Sabre\DAV\Auth\Backend\AbstractBasic;

/**
 * Basic authentication backend for CalDAV server.
 *
 * @since 9.5.0
 */
class Auth extends AbstractBasic
{
    protected $principalPrefix = Principal::PREFIX_USERS . '/';

    protected function validateUserPass($username, $password)
    {
        $auth = new \Auth();
        return $auth->login($username, $password, true);
    }
}
