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

use Glpi\Toolbox\Sanitizer;

include('../inc/includes.php');

Session::checkCentralAccess();

if (!isset($_REQUEST["action"])) {
    exit;
}

$extevent = new PlanningExternalEvent();

if ($_REQUEST["action"] == "get_events") {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(Planning::constructEventsArray($_REQUEST));
    exit;
}

if (($_POST["action"] ?? null) == "update_event_times") {
    echo Planning::updateEventTimes($_POST);
    exit;
}

if (($_POST["action"] ?? null) == "view_changed") {
    Planning::viewChanged($_POST['view']);
    exit;
}

if (($_POST["action"] ?? null) == "clone_event") {
    $extevent->check(-1, CREATE);
    echo Planning::cloneEvent($_POST['event']);
    exit;
}

if (($_POST["action"] ?? null) == "delete_event") {
    $extevent->check(-1, DELETE);
    echo Planning::deleteEvent($_POST['event']);
    exit;
}

if ($_REQUEST["action"] == "get_externalevent_template") {
    $key = 'planningexternaleventtemplates_id';
    if (
        isset($_POST[$key])
        && $_POST[$key] > 0
    ) {
        $template = new PlanningExternalEventTemplate();
        $template->getFromDB($_POST[$key]);

        $template->fields = Sanitizer::decodeHtmlSpecialCharsRecursive($template->fields);
        $template->fields['rrule'] = json_decode($template->fields['rrule'], true);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($template->fields, JSON_NUMERIC_CHECK);
        exit;
    }
}

Html::header_nocache();
header("Content-Type: text/html; charset=UTF-8");

if ($_REQUEST["action"] == "add_event_fromselect") {
    Planning::showAddEventForm($_REQUEST);
}

if ($_REQUEST["action"] == "add_event_sub_form") {
    Planning::showAddEventSubForm($_REQUEST);
}

if ($_REQUEST["action"] == "add_planning_form") {
    Planning::showAddPlanningForm();
}

if ($_REQUEST["action"] == "add_user_form") {
    Planning::showAddUserForm();
}

if ($_REQUEST["action"] == "add_group_users_form") {
    Planning::showAddGroupUsersForm();
}

if ($_REQUEST["action"] == "add_group_form") {
    Planning::showAddGroupForm();
}

if ($_REQUEST["action"] == "add_external_form") {
    Planning::showAddExternalForm();
}

if ($_REQUEST["action"] == "add_event_classic_form") {
    Planning::showAddEventClassicForm($_REQUEST);
}

if ($_REQUEST["action"] == "edit_event_form") {
    Planning::editEventForm($_REQUEST);
}

if ($_REQUEST["action"] == "get_filters_form") {
    Planning::showPlanningFilter();
}

if (($_POST["action"] ?? null) == "toggle_filter") {
    Planning::toggleFilter($_POST);
}

if (($_POST["action"] ?? null) == "color_filter") {
    Planning::colorFilter($_POST);
}

if (($_POST["action"] ?? null) == "delete_filter") {
    Planning::deleteFilter($_POST);
}

Html::ajaxFooter();
