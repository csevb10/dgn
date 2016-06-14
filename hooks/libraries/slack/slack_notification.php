<?php
/**
 * @file
 * Enter file description.
 */

if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  require_once "pantheon_slack_notification.php";
}
else {
  require_once "acquia_slack_notification.php";
}

$color  = isset($defaults['color']) ? $defaults['color'] : '#EFD01B';
$url    = isset($defaults['url']) ? $defaults['url'] : `drush @$site.$environment status | perl -F'/[\s:]+/' -lane '/Site URI/ && print $F[3]'`;
$icon   = isset($defaults['icon']) ? $defaults['icon'] : ':lightning_cloud:';
$type   = isset($type) ? $type : 'deploy';

// Build an array of fields to be rendered with Slack Attachments as a table
// attachment-style formatting:
// https://api.slack.com/docs/attachments
$fields = array(
  array(
    'title' => 'Site',
    'value' => $site,
    'short' => 'true'
  ),
  array( // Render Environment name with link to site, <http://{ENV}-{SITENAME}.pantheon.io|{ENV}>
    'title' => 'Environment',
    'value' => '<' . $url . '|' . $environment . '>',
    'short' => 'true'
  ),
);

if (isset($defaults['email'])) {
  $fields[] = array( // Render Name with link to Email from Commit message
    'title' => 'By',
    'value' => $defaults['email'],
    'short' => 'true'
  );
}

// Load our hidden credentials.
// See the README.md for instructions on storing secrets.
$secrets = _get_secrets(array('slack_url'), $defaults);

$fields[] = array(
  'title' => 'View Dashboard',
  'value' => '<' . $defaults['dashboard'] . '|View Dashboard>',
  'short' => 'true'
);

// Customize the message based on the workflow type.  Note that slack_notification.php
// must appear in your pantheon.yml for each workflow type you wish to send notifications on.
switch($type) {
  case 'deploy':
    // Find out what tag we are on and get the annotation.
    // $deploy_tag = `git describe --tags`;
    // $deploy_message = $_POST['deploy_message'];

    // Prepare the slack payload as per:
    // https://api.slack.com/incoming-webhooks
    $text = 'Deploy to the '. $environment;
    $text .= ' environment of '. $site . ' complete!';
    // $text .= "\n\n*DEPLOY MESSAGE*: $deploy_message";
    // Build an array of fields to be rendered with Slack Attachments as a table
    // attachment-style formatting:
    // https://api.slack.com/docs/attachments
    $fields[] = array(
      'title' => 'Deploy Message',
      'value' => $text,
      'short' => 'false'
    );
    break;
  /*
    case 'sync_code':
      // Get the committer, hash, and message for the most recent commit.
      $committer = `git log -1 --pretty=%cn`;
      $email = `git log -1 --pretty=%ce`;
      $message = `git log -1 --pretty=%B`;
      $hash = `git log -1 --pretty=%h`;

      // Prepare the slack payload as per:
      // https://api.slack.com/incoming-webhooks
      $text = 'Code sync to the ' . $_ENV['PANTHEON_ENVIRONMENT'] . ' environment of ' . $_ENV['PANTHEON_SITE_NAME'] . ' by ' . $_POST['user_email'] . "!\n";
      $text .= 'Most recent commit: ' . rtrim($hash) . ' by ' . rtrim($committer) . ': ' . $message;
      // Build an array of fields to be rendered with Slack Attachments as a table
      // attachment-style formatting:
      // https://api.slack.com/docs/attachments
      $fields += array(
        array(
          'title' => 'Commit',
          'value' => rtrim($hash),
          'short' => 'true'
        ),
        array(
          'title' => 'Commit Message',
          'value' => $message,
          'short' => 'false'
        )
      );
      break;

    default:
      $text = $_POST['qs_description'];
      break;
  */
}

$attachment = array(
  'fallback' => $text,
  'pretext' => 'Deploying :rocket:',
  'color' => $color, // Can either be one of 'good', 'warning', 'danger', or any hex color code
  'fields' => $fields,
);

_slack_notification($secrets['slack_url'], $secrets['slack_channel'], $secrets['slack_username'], $text, $attachment, $secrets['always_show_text'], $icon);


/**
 * Get secrets from secrets file.
 *
 * @param array $requiredKeys  List of keys in secrets file that must exist.
 */
function _get_secrets($requiredKeys, $defaults)  {
  $secretsFile = $_SERVER['HOME'] . '/private/slack_settings.json';
  if (!file_exists($secretsFile)) {
    die('No secrets file found. Aborting!');
  }
  $secretsContents = file_get_contents($secretsFile);
  $secrets = json_decode($secretsContents, 1);
  if ($secrets == FALSE) {
    die('Could not parse json in secrets file. Aborting!');
  }
  $secrets += $defaults;
  $missing = array_diff($requiredKeys, array_keys($secrets));
  if (!empty($missing)) {
    die('Missing required keys in json secrets file: ' . implode(',', $missing) . '. Aborting!');
  }
  return $secrets;
}

/**
 * Send a notification to slack
 */
function _slack_notification($slack_url, $channel, $username, $text, $attachment, $alwaysShowText = false, $icon = ':lightning_cloud:')  {
  $attachment['fallback'] = $text;
  $post = array(
    'username' => $username,
    'channel' => $channel,
    'icon_emoji' => $icon,
    'attachments' => array($attachment)
  );
  if ($alwaysShowText) {
    $post['text'] = $text;
  }
  $payload = json_encode($post);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $slack_url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  // Watch for messages with `terminus workflows watch --site=SITENAME`
  print("\n==== Posting to Slack ====\n");
  $result = curl_exec($ch);
  print("RESULT: $result");
  // $payload_pretty = json_encode($post,JSON_PRETTY_PRINT); // Uncomment to debug JSON
  // print("JSON: $payload_pretty"); // Uncomment to Debug JSON
  print("\n===== Post Complete! =====\n");
  curl_close($ch);
}
