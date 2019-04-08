<?php

$mailer = JFactory::getMailer();
$config = JFactory::getConfig();

$sender = array(
    $config->get( 'mailfrom' ),
    $config->get( 'fromname' )
);

$mailer->setSender($sender);

$user = JFactory::getUser();
$recipient = $user->email;

$mailer->addRecipient($recipient);
