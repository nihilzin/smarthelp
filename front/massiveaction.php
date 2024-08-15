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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

Session::checkLoginUser();

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

try {
    $ma = new MassiveAction($_POST, $_GET, 'process');
} catch (\Throwable $e) {
    Html::popHeader(__('Bulk modification error'), $_SERVER['PHP_SELF']);

    echo "<div class='center'><img src='" . $CFG_GLPI["root_doc"] . "/pics/warning.png' alt='" .
      __s('Warning') . "'><br><br>";
    echo "<span class='b'>" . $e->getMessage() . "</span><br>";
    Html::displayBackLink();
    echo "</div>";

    Html::popFooter();
    exit();
}
Html::popHeader(__('Bulk modification'), $_SERVER['PHP_SELF']);

$results   = $ma->process();

$nbok       = $results['ok'];
$nbnoaction = $results['noaction'];
$nbko       = $results['ko'];
$nbnoright  = $results['noright'];

$msg_type = INFO;
if ($nbnoaction > 0 && $nbok === 0 && $nbko === 0 && $nbnoright === 0) {
    $message = __('Operation was done but no action was required');
} else if ($nbok == 0) {
    $message = __('Failed operation');
    $msg_type = ERROR;
} else if ($nbnoright || $nbko) {
    $message = __('Operation performed partially successful');
    $msg_type = WARNING;
} else {
    $message = __('Operation successful');
    if ($nbnoaction > 0) {
        $message .= "<br>" . sprintf(__('(%1$d items required no action)'), $nbnoaction);
    }
}
if ($nbnoright || $nbko) {
   //TRANS: %$1d and %$2d are numbers
    $message .= "<br>" . sprintf(
        __('(%1$d authorizations problems, %2$d failures)'),
        $nbnoright,
        $nbko
    );
}
Session::addMessageAfterRedirect($message, false, $msg_type);
if (isset($results['messages']) && is_array($results['messages']) && count($results['messages'])) {
    foreach ($results['messages'] as $message) {
        Session::addMessageAfterRedirect($message, false, ERROR);
    }
}
Html::redirect($results['redirect']);

Html::popFooter();
