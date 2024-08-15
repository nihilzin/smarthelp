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

$SECURITY_STRATEGY = 'no_check'; // Anonymous access may be allowed by configuration.

include('../inc/includes.php');

if ((int)$CFG_GLPI['use_anonymous_helpdesk'] === 0) {
    Html::redirect($CFG_GLPI["root_doc"] . "/front/central.php");
}

Glpi\Application\View\TemplateRenderer::getInstance()->display('anonymous_helpdesk.html.twig', [
    'card_md_width' => true,
    'title'         => "Helpdesk",
]);
