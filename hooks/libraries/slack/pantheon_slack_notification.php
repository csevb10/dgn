<?php
/**
 * @file
 * Enter file description.
 */

// Important constants :)
$pantheon_yellow = '#EFD01B';

$site         = $_ENV['PANTHEON_SITE_NAME'];
$environment  = $_ENV['PANTHEON_ENVIRONMENT'];
$workflow     = ucfirst($_POST['stage']) . ' ' . str_replace('_', ' ',  $_POST['wf_type']);

// Default values for parameters
$defaults = array(
  'slack_channel' => '@csevb10',
  'slack_username' => 'Pantheon',
  'always_show_text' => false,
  'dashboard' => 'https://dashboard.pantheon.io/sites/'. $site .'#'. $environment .'/deploys',
  'color' => '#EFD01B',
  'email' => $_POST['user_email'],
  'url' => 'http://' . $environment . '-' . $site . '.pantheonsite.io',
);
