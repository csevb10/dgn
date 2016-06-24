#!/usr/bin/env bash

# Authenticate to Terminus and pull our DB from Pantheon.
terminus auth login --machine-token=$MACHINETOKEN
terminus drush sql-dump --site=$PSITE --env=$PENV > db.sql
