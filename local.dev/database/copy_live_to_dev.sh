#!/bin/bash

livedb="/var/www/money/local.dev/database"
devdb="/var/www/money-dev/local.dev/database"

~/.cmp/run_command.sh mysql -f dump "$devdb/project.sql" "$livedb/dbconfig.php"
~/.cmp/run_command.sh mysql -f load "$devdb/project.sql" "$devdb/dbconfig.php"
