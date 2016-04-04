<?php
namespace MCPI;

define( 'DS', DIRECTORY_SEPARATOR );
define( 'EOL', PHP_EOL );

define( 'DIR_ROOT', realpath(__DIR__ . DS . '..') . DS );

define( 'DIR_CONFIG', DIR_ROOT . 'config' . DS );
define( 'DIR_CODE', DIR_ROOT . 'code' . DS );
define( 'DIR_TEMPLATES', DIR_ROOT . 'templates' . DS );
define( 'DIR_TMP', DIR_ROOT . 'tmp' . DS );

define( 'DIR_PUBLIC', DIR_ROOT . 'public' . DS );
define( 'DIR_UPLOAD', DIR_PUBLIC . 'upload' . DS );
