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

namespace Glpi\CalDAV\Plugin;

use CommonDBTM;
use Glpi\CalDAV\Backend\Principal;
use Glpi\CalDAV\Traits\CalDAVPrincipalsTrait;
use Glpi\CalDAV\Traits\CalDAVUriUtilTrait;
use PlanningExternalEvent;
use Sabre\CalDAV\Calendar;
use Sabre\CalDAV\CalendarObject;
use Sabre\DAVACL\IACL;
use Sabre\DAVACL\Plugin;
use Session;

/**
 * ACL plugin for CalDAV server.
 *
 * @since 9.5.0
 */
class Acl extends Plugin
{
    use CalDAVPrincipalsTrait;
    use CalDAVUriUtilTrait;

    public $principalCollectionSet = [
        Principal::PREFIX_GROUPS,
        Principal::PREFIX_USERS,
    ];

    public $allowUnauthenticatedAccess = false;

    public function getAcl($node)
    {
        if (is_string($node)) {
            $node = $this->server->tree->getNodeForPath($node);
        }

        $acl = parent::getAcl($node);

        if (
            !($node instanceof IACL) || ($owner_path = $node->getOwner()) === null
            || !$this->canViewPrincipalObjects($owner_path)
        ) {
            return $acl;
        }

        $acl[] = [
            'principal' => '{DAV:}authenticated',
            'privilege' => '{DAV:}read',
            'protected' => true,
        ];

        if ($node instanceof Calendar && Session::haveRight(PlanningExternalEvent::$rightname, UPDATE)) {
           // If user can update external events, then he is able to write on calendar to create new events.
            $acl[] = [
                'principal' => '{DAV:}authenticated',
                'privilege' => '{DAV:}write',
                'protected' => true,
            ];
        } else if ($node instanceof CalendarObject) {
            $item = $this->getCalendarItemForPath($node->getName());
            if ($item instanceof CommonDBTM && $item->can($item->fields['id'], UPDATE)) {
                $acl[] = [
                    'principal' => '{DAV:}authenticated',
                    'privilege' => '{DAV:}write',
                    'protected' => true,
                ];
            }
        }

        return $acl;
    }
}
