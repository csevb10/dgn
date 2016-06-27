#!/usr/bin/env bash

# Authenticate to Acquia and pull our DB from Acquia.
drush acquia-update
drush @$PAASSITE.$PAASENV sql-dump > db.sql
