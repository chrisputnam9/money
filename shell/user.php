<?php

print_r($argv);

$pw = $argv[1];

echo password_hash($pw, PASSWORD_DEFAULT);
