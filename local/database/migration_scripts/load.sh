#!/bin/bash
echo "Dropping Project DB"
mysql -u root < /media/database/migration_scripts/drop.sql
echo "Recreating Project DB"
mysql -u root < /media/database/migration_scripts/init.sql
echo "Loading DB Schema"
mysql -u root project < /media/database/schema.sql
echo "Loading DB Data"
mysql -u root project < /media/database/schema.sql
