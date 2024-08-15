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

Session::checkSeveralRightsOr(['rule_dictionnary_dropdown' => READ,
    'rule_dictionnary_software' => READ
]);

Html::header(_n('Dictionary', 'Dictionaries', Session::getPluralNumber()), $_SERVER['PHP_SELF'], "admin", "dictionnary", -1);

echo TemplateRenderer::getInstance()->render(
    'pages/admin/rules_list.html.twig',
    [
        'rules_group' => RuleCollection::getDictionnaries()
    ]
);
Html::footer();
