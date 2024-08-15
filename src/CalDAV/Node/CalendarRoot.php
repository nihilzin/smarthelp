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

namespace Glpi\CalDAV\Node;

use Glpi\CalDAV\Backend\Calendar;
use Glpi\CalDAV\Backend\Principal;

/**
 * Calendar root node for CalDAV server.
 *
 * @since 9.5.0
 */
class CalendarRoot extends \Sabre\CalDAV\CalendarRoot
{
    public function getName()
    {

        $calendarPath = '';
        switch ($this->principalPrefix) {
            case Principal::PREFIX_GROUPS:
                $calendarPath = Calendar::PREFIX_GROUPS;
                break;
            case Principal::PREFIX_USERS:
                $calendarPath = Calendar::PREFIX_USERS;
                break;
        }

       // Return calendar path relative to calendar root path
        return preg_replace(
            '/^' . preg_quote(Calendar::CALENDAR_ROOT . '/', '/') . '/',
            '',
            $calendarPath
        );
    }
}
