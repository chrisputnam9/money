#!/bin/bash

livedb="/var/www/money/local.dev/database"
devdb="/var/www/money-dev/local.dev/database"

c m dump "$devdb/project.sql" "$livedb/dbconfig.php"
c m dump "$devdb/project.sql" "$devdb/dbconfig.php"
