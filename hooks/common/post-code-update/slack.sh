#!/bin/sh
#
# Cloud Hook: post-code-deploy
#
# The post-code-deploy hook is run whenever you use the Workflow page to
# deploy new code to an environment, either via drag-drop or by selecting
# an existing branch or tag from the Code drop-down list. See
# ../README.md for details.
#
# Usage: post-code-deploy site target-env source-branch deployed-tag repo-url
#                         repo-type

site="$1"
env="$2"
source_branch="$3"
deployed_tag="$4"
repo_url="$5"
repo_type="$6"

# If there are no values, we're on Acquia, else, we will pass these values in.
if [ -z "$7" ]; then username="Acquia Cloud"; else username="$7"; fi
if [ -z "$8" ]; then channel="@csevb10"; else channel="$8"; fi
if [ -z "$9" ]; then domain=`drush @$site.$env status | perl -F'/[\s:]+/' -lane '/Site URI/ && print $F[3]'`; else domain="$9"; fi
if [ -z "$10" ]; then dashboard="https://insight.acquia.com/site-list"; else dashboard="$10"; fi


# Load the Slack webhook URL (which is not stored in this repo).
. $HOME/private/slack_settings

environment="<http://$domain|$env>"

# Post deployment notice to Slack
curl \
  -X POST \
  --data-urlencode \
  "payload={ \
    \"channel\": \"$channel\", \
    \"username\": \"$username\", \
    \"text\": \"A new deployment has been made to *$env* using tag *$deployed_tag*.\", \
    \"icon_emoji\": \":information_source:\", \
    \"attachments\": [{ \
      \"fields\": [ \
        { \
          \"title\": \"Site\", \
          \"value\": \"$site\", \
          \"short\": true \
        }, \
        { \
          \"title\": \"Environment\", \
          \"value\": \"$environment\", \
          \"short\": true \
        }, \
        { \
          \"title\": \"View Dashboard\", \
          \"value\": \"<$dashboard|Dashboard>\", \
          \"short\": true \
        } \
      ] \
    }] \
  }" \
  $SLACK_WEBHOOK_URL
