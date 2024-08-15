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

include('../inc/includes.php');

Session::checkCentralAccess();

Html::header(Rule::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "admin", "rule", -1);

echo TemplateRenderer::getInstance()->render(
    'pages/admin/rules_list.html.twig',
    [
        'rules_group' => [
            [
                'type'    => __('Rule type'),
                'entries' => RuleCollection::getRules(),
            ],
        ]
    ]
);

Html::footer();
