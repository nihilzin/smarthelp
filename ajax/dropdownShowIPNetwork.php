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

include('../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

$network = new IPNetwork();

if ($_POST['ipnetworks_id'] && $network->can($_POST['ipnetworks_id'], READ)) {
    echo "<br>\n";
    echo "<a href='" . $network->getLinkURL() . "'>" . $network->fields['completename'] . "</a><br>\n";

    $address = $network->getAddress()->getTextual();
    $netmask = $network->getNetmask()->getTextual();
    $gateway = $network->getGateway()->getTextual();

    $start   = new IPAddress();
    $end     = new IPAddress();

    $network->computeNetworkRange($start, $end);

   //TRANS: %1$s is address, %2$s is netmask
    printf(__('IP network: %1$s/%2$s') . "<br>\n", $address, $netmask);
    printf(__('First/last addresses: %1$s/%2$s'), $start->getTextual(), $end->getTextual());
    if (!empty($gateway)) {
        echo "<br>\n";
        printf(__('Gateway: %s') . "\n", $gateway);
    }
}
