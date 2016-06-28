#!/usr/bin/env bash

# Authenticate to Acquia and pull our DB from Acquia.
ssh $PAASSITE.$PAASENV@$PAASURL
drush sql-dump $PAASITE@$PAASENV > db.sql
exit
scp $PAASSITE.$PAASENV@$PAASURL:db.sql db.sql
