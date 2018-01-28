<?php

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests'])
    ->exclude('__snapshots__')
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@PHPUnit60Migration:risky' => true,
        'declare_strict_types' => true,
        'single_blank_line_before_namespace' => true,
        'array_syntax' => ['syntax' => 'short'],
        'is_null' => true,
        'no_unused_imports' => true,
        'trailing_comma_in_multiline_array' => true,
    ])
    ->setFinder($finder)
;