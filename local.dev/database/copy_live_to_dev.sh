#!/bin/bash

livedb="/var/www/money/local.dev/database"
devdb="/var/www/money-dev/local.dev/database"

c mysql dump "$devdb/project.sql" "$livedb/dbconfig.php"
c mysql dump "$devdb/project.sql" "$devdb/dbconfig.php"
