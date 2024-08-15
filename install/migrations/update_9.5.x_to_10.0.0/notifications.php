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


use Glpi\Toolbox\Sanitizer;

/**
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

/** User mention notification */
$notification_exists = countElementsInTable('glpi_notifications', ['itemtype' => 'Ticket', 'event' => 'user_mention']) > 0;
if (!$notification_exists) {
    $DB->insertOrDie(
        'glpi_notifications',
        [
            'id'              => null,
            'name'            => 'New user mentioned',
            'entities_id'     => 0,
            'itemtype'        => 'Ticket',
            'event'           => 'user_mention',
            'comment'         => '',
            'is_recursive'    => 1,
            'is_active'       => 1,
            'date_creation'   => new \QueryExpression('NOW()'),
            'date_mod'        => new \QueryExpression('NOW()')
        ],
        '10.0 Add user mention notification'
    );
    $notification_id = $DB->insertId();

    $notificationtemplate = new NotificationTemplate();
    if ($notificationtemplate->getFromDBByCrit(['name' => 'Tickets', 'itemtype' => 'Ticket'])) {
        $DB->insertOrDie(
            'glpi_notifications_notificationtemplates',
            [
                'notifications_id'         => $notification_id,
                'mode'                     => Notification_NotificationTemplate::MODE_MAIL,
                'notificationtemplates_id' => $notificationtemplate->fields['id'],
            ],
            '10.0 Add user mention notification template'
        );
    }

    $DB->insertOrDie(
        'glpi_notificationtargets',
        [
            'items_id'         => '39',
            'type'             => '1',
            'notifications_id' => $notification_id,
        ],
        '10.0 Add user mention notification target'
    );
}
/** /User mention notification */

/** Fix non encoded notifications */
$notifications = getAllDataFromTable('glpi_notificationtemplatetranslations');
foreach ($notifications as $notification) {
    if ($notification['content_html'] !== null && preg_match('/(<|>|(&(?!#?[a-z0-9]+;)))/i', $notification['content_html']) === 1) {
        $migration->addPostQuery(
            $DB->buildUpdate(
                'glpi_notificationtemplatetranslations',
                [
                    'content_html' => Sanitizer::sanitize($notification['content_html']),
                ],
                [
                    'id' => $notification['id'],
                ]
            )
        );
    }
}
/** Fix non encoded notifications */
