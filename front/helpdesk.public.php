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

use Glpi\Application\View\TemplateRenderer;

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

// Change profile system
if (isset($_REQUEST['newprofile'])) {
    if (isset($_SESSION["glpiprofiles"][$_REQUEST['newprofile']])) {
        Session::changeProfile($_REQUEST['newprofile']);

        if (Session::getCurrentInterface() == "central") {
            Html::redirect($CFG_GLPI['root_doc'] . "/front/central.php");
        } else {
            Html::redirect($_SERVER['PHP_SELF']);
        }
    } else {
        Html::redirect(preg_replace("/entities_id=.*/", "", $_SERVER['HTTP_REFERER']));
    }
}

// Manage entity change
if (isset($_GET["active_entity"])) {
    if (!isset($_GET["is_recursive"])) {
        $_GET["is_recursive"] = 0;
    }
    if (Session::changeActiveEntities($_GET["active_entity"], $_GET["is_recursive"])) {
        if ($_GET["active_entity"] == $_SESSION["glpiactive_entity"]) {
            Html::redirect(preg_replace("/(\?|&|" . urlencode('?') . "|" . urlencode('&') . ")?(entities_id|active_entity).*/", "", $_SERVER['HTTP_REFERER']));
        }
    }
}

// Redirect management
if (isset($_GET["redirect"])) {
    Toolbox::manageRedirect($_GET["redirect"]);
}

// redirect if no create ticket right
if (
    !Session::haveRight('ticket', CREATE)
    && !Session::haveRight('reminder_public', READ)
    && !Session::haveRight("rssfeed_public", READ)
) {
    if (
        Session::haveRight('followup', ITILFollowup::SEEPUBLIC)
        || Session::haveRight('task', TicketTask::SEEPUBLIC)
        || Session::haveRightsOr('ticketvalidation', [TicketValidation::VALIDATEREQUEST,
            TicketValidation::VALIDATEINCIDENT
        ])
    ) {
        Html::redirect($CFG_GLPI['root_doc'] . "/front/ticket.php");
    } else if (Session::haveRightsOr('reservation', [READ, ReservationItem::RESERVEANITEM])) {
        Html::redirect($CFG_GLPI['root_doc'] . "/front/reservationitem.php");
    } else if (Session::haveRight('knowbase', KnowbaseItem::READFAQ)) {
        Html::redirect($CFG_GLPI['root_doc'] . "/front/helpdesk.faq.php");
    }
}

Session::checkValidSessionId();

if (isset($_GET['create_ticket'])) {
    Html::helpHeader(__('New ticket'), "create_ticket");
    $ticket = new Ticket();
    $ticket->showFormHelpdesk(Session::getLoginUserID());
} else {
    Html::helpHeader(__('Home'));

    $password_alert = "";
    $user = new User();
    $user->getFromDB(Session::getLoginUserID());

    $ticket_summary = "";
    $survey_list    = "";
    if (Session::haveRight('ticket', CREATE)) {
        $ticket_summary = Ticket::showCentralCount(true, false);
        $survey_list    = Ticket::showCentralList(0, "survey", false, false);
    }

    $reminder_list = "";
    if (Session::haveRight("reminder_public", READ)) {
        $reminder_list = Reminder::showListForCentral(false, false);
    }

    $rss_feed = "";
    if (Session::haveRight("rssfeed_public", READ)) {
        $rss_feed = RSSFeed::showListForCentral(false, false);
    }

    $kb_popular    = "";
    $kb_recent     = "";
    $kb_lastupdate = "";
    if (Session::haveRight('knowbase', KnowbaseItem::READFAQ)) {
        $kb_popular    = KnowbaseItem::showRecentPopular("popular", false);
        $kb_recent     = KnowbaseItem::showRecentPopular("recent", false);
        $kb_lastupdate = KnowbaseItem::showRecentPopular("lastupdate", false);
    }

    Html::requireJs('masonry');
    TemplateRenderer::getInstance()->display('pages/self-service/home.html.twig', [
        'password_alert' => $user->getPasswordExpirationMessage(),
        'ticket_summary' => $ticket_summary,
        'survey_list'    => $survey_list,
        'reminder_list'  => $reminder_list,
        'rss_feed'       => $rss_feed,
        'kb_popular'     => $kb_popular,
        'kb_recent'      => $kb_recent,
        'kb_lastupdate'  => $kb_lastupdate,
    ]);
}

Html::helpFooter();
