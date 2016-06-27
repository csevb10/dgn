#!/usr/bin/env bash

ssh $PAASSITE@$PAASENV@$PAASURL
drush sql-dump $PAASITE@$PAASENV > db.sql
exit
scp $PAASSITE@$PAASENV@$PAASURL:db.sql db.sql
