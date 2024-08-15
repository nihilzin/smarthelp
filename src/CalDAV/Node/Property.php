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

/**
 * Calendar node properties constants.
 *
 * @since 9.5.0
 */
class Property
{
    const CAL_COLOR                = '{http://apple.com/ns/ical/}calendar-color';
    const CAL_DESCRIPTION          = '{urn:ietf:params:xml:ns:caldav}calendar-description';
    const CAL_SUPPORTED_COMPONENTS = '{urn:ietf:params:xml:ns:caldav}supported-calendar-component-set';
    const CAL_USER_TYPE            = '{urn:ietf:params:xml:ns:caldav}calendar-user-type';
    const DISPLAY_NAME             = '{DAV:}displayname';
    const PRIMARY_EMAIL            = '{http://sabredav.org/ns}email-address';
    const RESOURCE_TYPE            = '{DAV:}resourcetype';
    const USERNAME                 = '{http://glpi-project.org/ns}username';
}
