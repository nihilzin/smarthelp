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

$AJAX_INCLUDE = 1;
if (strpos($_SERVER['PHP_SELF'], "uemailUpdate.php")) {
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
}

Session::checkLoginUser();

if (
    (isset($_POST['field']) && ($_POST["value"] > 0))
    || (isset($_POST['allow_email']) && $_POST['allow_email'])
) {
    if (preg_match('/[^a-z_\-0-9]/i', $_POST['field'])) {
        throw new \RuntimeException('Invalid field provided!');
    }

    $default_email = "";
    $emails        = [];
    if (isset($_POST['typefield']) && ($_POST['typefield'] == 'supplier')) {
        $supplier = new Supplier();
        if ($supplier->getFromDB($_POST["value"])) {
            $default_email = $supplier->fields['email'];
        }
    } else {
        $user          = new User();
        if ($user->getFromDB($_POST["value"])) {
            $default_email = $user->getDefaultEmail();
            $emails        = $user->getAllEmails();
        }
    }

    $user_index = $_POST['_user_index'] ?? 0;

    $default_notif = $_POST['use_notification'][$user_index] ?? true;

    if (
        isset($_POST['alternative_email'][$user_index])
        && !empty($_POST['alternative_email'][$user_index])
        && empty($default_email)
    ) {
        if (NotificationMailing::isUserAddressValid($_POST['alternative_email'][$user_index])) {
            $default_email = $_POST['alternative_email'][$user_index];
        } else {
            throw new \RuntimeException('Invalid email provided!');
        }
    }

    $switch_name = $_POST['field'] . '[use_notification][]';
    echo "<div class='my-1 d-flex align-items-center'>
         <label  for='email_fup_check'>
            <i class='far fa-envelope me-1'></i>
            " . __('Email followup') . "
         </label>
         <div class='ms-2'>
            " . Dropdown::showYesNo($_POST['field'] . '[use_notification][]', $default_notif, -1, ['display' => false]) . "
         </div>
      </div>";

    $email_string = '';
   // Only one email
    if (
        (count($emails) == 1)
        && !empty($default_email)
        && NotificationMailing::isUserAddressValid($default_email[$user_index])
    ) {
        $email_string =  $default_email[$user_index];
       // Clean alternative email
        echo "<input type='hidden' size='25' name='" . $_POST['field'] . "[alternative_email][]'
             value=''>";
    } else if (count($emails) > 1) {
       // Several emails : select in the list
        $emailtab = [];
        foreach ($emails as $new_email) {
            if ($new_email != $default_email) {
                $emailtab[$new_email] = $new_email;
            } else {
                $emailtab[''] = $new_email;
            }
        }
        $email_string = Dropdown::showFromArray(
            $_POST['field'] . "[alternative_email][]",
            $emailtab,
            [
                'value'   => '',
                'display' => false
            ]
        );
    } else {
        $email_string = "<input type='mail' class='form-control' name='" . $_POST['field'] . "[alternative_email][]'
                        value='" . htmlentities($default_email, ENT_QUOTES, 'utf-8') . "'>";
    }

    echo "$email_string";
}

Ajax::commonDropdownUpdateItem($_POST);
