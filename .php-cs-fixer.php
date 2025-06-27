<?php

/**
 * Local override for PHP-CS-Fixer configuration.
 *
 * This file should be .gitignored.
 */

$phpCsFixerConfig = require __DIR__.'/.php-cs-fixer.dist.php';
$rules = $phpCsFixerConfig->getRules();

return $phpCsFixerConfig->setRules(array_merge($rules, [
    '@PhpCsFixer' => true,
]));
