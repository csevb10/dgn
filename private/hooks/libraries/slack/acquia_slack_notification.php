<?php
/**
 * @file
 * Enter file description.
 */

$site         = $argv[1];
$environment  = $argv[2];
$db           = $argv[3];
$source       = $argv[4];

// Default values for parameters
$defaults = array(
  'slack_channel' => '@csevb10',
  'slack_username' => 'Acquia Cloud',
  'always_show_text' => false,
  'icon' => ':information_source:',
  'dashboard' => 'https://insight.acquia.com/site-list',
  'color' => '#018DC7',
  'icon_url' => 'https://media.glassdoor.com/sqll/347852/acquia-squarelogo-1405365783760.png',
  'url' => `drush @$site.$environment status | perl -F'/[\s:]+/' -lane '/Site URI/ && print \$F[3]'`,
);
