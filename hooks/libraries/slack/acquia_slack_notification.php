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
);
