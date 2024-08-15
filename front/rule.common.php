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

/**
 * Following variables have to be defined before inclusion of this file:
 * @var RuleCollection $rulecollection
 */

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$rulecollection->checkGlobal(READ);

if (isset($_POST["action"])) {
    $rulecollection->checkGlobal(UPDATE);
    $rulecollection->changeRuleOrder($_POST["id"], $_POST["action"], $_POST['condition']);
    Html::back();
} else if (isset($_POST["reinit"]) || isset($_GET['reinit'])) {
   //reinitialize current rules
    $ruleclass = $rulecollection->getRuleClass();
    if ($ruleclass::initRules()) {
        Session::addMessageAfterRedirect(
            sprintf(
            //TRANS: first parameter is the rule type name
                __('%1$s has been reset.'),
                $rulecollection->getTitle()
            )
        );
    } else {
        Session::addMessageAfterRedirect(
            sprintf(
                //TRANS: first parameter is the rule type name
                __('%1$s reset failed.'),
                $rulecollection->getTitle()
            ),
            false,
            ERROR
        );
    }
    Html::back();
} else if (isset($_POST["replay_rule"]) || isset($_GET["replay_rule"])) {
   // POST and GET needed to manage reload
    $rulecollection->checkGlobal(UPDATE);

   // Current time
    $start = explode(" ", microtime());
    $start = $start[0] + $start[1];

   // Limit computed from current time
    $max = get_cfg_var("max_execution_time");
    $max = $start + ($max > 0 ? $max / 2.0 : 30.0);

    Html::header(
        Rule::getTypeName(Session::getPluralNumber()),
        $_SERVER['PHP_SELF'],
        "admin",
        $rulecollection->menu_type,
        $rulecollection->menu_option
    );

    if (
        !(isset($_POST['replay_confirm']) || isset($_GET['offset']))
        && $rulecollection->warningBeforeReplayRulesOnExistingDB($_SERVER['PHP_SELF'])
    ) {
        Html::footer();
        exit();
    }

    echo "<table class='tab_cadrehov'>";

    echo "<tr><th><div class='relative b'>" . $rulecollection->getTitle() . "<br>" .
         __('Replay the rules dictionary') . "</div></th></tr>\n";
    echo "<tr><td class='center'>";
    Html::createProgressBar(__('Work in progress...'));
    echo "</td></tr>\n";
    echo "</table>";

    if (!isset($_GET['offset'])) {
       // First run
        $offset       = $rulecollection->replayRulesOnExistingDB(0, $max, [], $_POST);
        $manufacturer = (isset($_POST["manufacturer"]) ? $_POST["manufacturer"] : 0);
    } else {
       // Next run
        $offset       = $rulecollection->replayRulesOnExistingDB(
            $_GET['offset'],
            $max,
            [],
            $_GET
        );
        $manufacturer = $_GET["manufacturer"];

       // global start for stat
        $start = $_GET["start"];
    }

    if ($offset < 0) {
       // Work ended
        $end   = explode(" ", microtime());
        $duree = round($end[0] + $end[1] - $start);
        Html::changeProgressBarMessage(sprintf(
            __('Task completed in %s'),
            Html::timestampToString($duree)
        ));
        echo "<a href='" . $_SERVER['PHP_SELF'] . "'>" . __('Back') . "</a>";
    } else {
       // Need more work
        Html::redirect($_SERVER['PHP_SELF'] . "?start=$start&replay_rule=1&offset=$offset&manufacturer=" .
                     "$manufacturer");
    }

    Html::footer(true);
    exit();
}

Html::header(
    Rule::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    'admin',
    $rulecollection->menu_type,
    $rulecollection->menu_option
);

$rulecollection->display([
    'display_criterias' => true,
    'display_actions'   => true,
]);
Html::footer();
