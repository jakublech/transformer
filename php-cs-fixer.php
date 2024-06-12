<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfig;

return (new Config())
    ->setParallelConfig(new ParallelConfig(8, 20))
    ->setFinder(
        (new Finder())
            ->in(__DIR__)
            ->exclude([
                '.github',
                'vendor',
            ]),
    )
    ->setHideProgress(true)
    ->setCacheFile('var/php-cs-fixer.cache')
    ->setRules([
        '@PhpCsFixer' => true,
        '@PER-CS2.0' => true,
        'concat_space' => ['spacing' => 'one'],
        'date_time_immutable' => true,
        'declare_strict_types' => true,
        'final_class' => true,
        'global_namespace_import' => true,
        'multiline_whitespace_before_semicolons' => true,
        'spaces_inside_parentheses' => false,
        'void_return' => true,
        'single_line_empty_body' => false,
        'cast_spaces' => false,
        'php_unit_internal_class' => false,
        'trailing_comma_in_multiline' => [
            'after_heredoc' => true,
            'elements' => ['arguments', 'arrays', 'match', 'parameters'],
        ],
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'none',
                'method' => 'one',
                'property' => 'none',
                'trait_import' => 'none',
                'case' => 'none',
            ],
        ],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'case',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
            ],
        ],
    ]);
