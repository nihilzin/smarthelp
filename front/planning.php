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

if (isset($_GET['genical'])) {
    // A new sssion is generated and destroyed during the ical/webcal export.
    // Prevent sending cookies to browser to ensure that user will not be disconnected when using the export feature.
    // It will also prevent to send a session cookie related to another user in case an error made the script exit before session destroying.
    ini_set('session.use_cookies', 0);

    if (isset($_GET['token'])) {
        $SECURITY_STRATEGY = 'no_check'; // Token based access for ical/webcal access can be made anonymously.
    }
}

include('../inc/includes.php');

if (!isset($_GET['genical'])) {
    Session::checkRight("planning", READ);
}

if (!isset($_GET["uID"])) {
    if (
        ($uid = Session::getLoginUserID())
        && !Session::haveRight("planning", Planning::READALL)
    ) {
        $_GET["uID"] = $uid;
    } else {
        $_GET["uID"] = 0;
    }
}

if (!isset($_GET["gID"])) {
    $_GET["gID"] = 0;
}

if (!isset($_GET["limititemtype"])) {
    $_GET["limititemtype"] = "";
}

// Normal call via $_GET
if (isset($_GET['checkavailability'])) {
    Html::popHeader(__('Availability'));

    Planning::checkAvailability($_GET);
    Html::popFooter();
} else if (isset($_GET['genical'])) {
    if (isset($_GET['token'])) {
       // Check user token
        $user = Session::authWithToken(
            $_GET['token'],
            'personal_token',
            $_GET['entities_id'] ?? null,
            $_GET['is_recursive'] ?? null
        );
        if ($user) {
            if (isset($_GET['entities_id']) && isset($_GET['is_recursive'])) {
               // load entities & profiles
               // needed to pass canViewItem() in populatePlanning functions in case of ical export
                $_SESSION["glpidefault_entity"]  = $user->fields['entities_id'];
                Session::initEntityProfiles($user->getID());
                if (isset($_SESSION['glpiprofiles'][$user->fields['profiles_id']])) {
                    Session::changeProfile($user->fields['profiles_id']);
                } else {
                    Session::changeProfile(key($_SESSION['glpiprofiles']));
                }
            }
            //// check if the request is valid: rights on uID / gID
            // First check mine : user then groups
            $ismine = false;
            if ($user->getID() == $_GET["uID"]) {
                $ismine = true;
            }
            // Check groups if have right to see
            if (!$ismine && ($_GET["gID"] !== 0)) {
                if ($_GET["gID"] === 'mine') {
                    $ismine = true;
                } else {
                    $entities = Profile_User::getUserEntitiesForRight(
                        $user->getID(),
                        Planning::$rightname,
                        Planning::READGROUP
                    );
                    $groups   = Group_User::getUserGroups($user->getID());
                    foreach ($groups as $group) {
                        if (
                            ($_GET["gID"] == $group['id'])
                            && in_array($group['entities_id'], $entities)
                        ) {
                            $ismine = true;
                        }
                    }
                }
            }

            $canview = false;
            // If not mine check global right
            if (!$ismine) {
               // First check user
                $entities = Profile_User::getUserEntitiesForRight(
                    $user->getID(),
                    Planning::$rightname,
                    Planning::READALL
                );
                if ($_GET["uID"]) {
                    $userentities = Profile_User::getUserEntities($user->getID());
                    $intersect    = array_intersect($entities, $userentities);
                    if (count($intersect)) {
                        $canview = true;
                    }
                }
               // Else check group
                if (!$canview && $_GET['gID']) {
                    $group = new Group();
                    if ($group->getFromDB($_GET['gID'])) {
                        if (in_array($group->getEntityID(), $entities)) {
                            $canview = true;
                        }
                    }
                }
            }

            if ($ismine || $canview) {
                Planning::generateIcal($_GET["uID"], $_GET["gID"], $_GET["limititemtype"]);
                Session::destroy();
            }
        }
    }
} else {
    Html::header(__('Planning'), $_SERVER['PHP_SELF'], "helpdesk", "planning");

    Session::checkRightsOr('planning', [Planning::READALL, Planning::READMY]);

    if (!isset($_GET["date"]) || empty($_GET["date"])) {
        $_GET["date"] = date("Y-m-d");
    }
    if (!isset($_GET["type"])) {
        $_GET["type"] = "week";
    }
    $planning = new Planning();
    $planning->display($_GET);

    Html::footer();
}
