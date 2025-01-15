<?php

$finder = PhpCsFixer\Finder::create()
    ->notPath('bootstrap/cache')
    ->notPath('storage')
    ->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);
$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@Symfony'                                      => true,
        '@PSR12'                                        => true,
        'align_multiline_comment'                       => true,
        'array_indentation'                             => true,
        'array_syntax'                                  => ['syntax' => 'short'],
        'combine_consecutive_issets'                    => true,
        'combine_consecutive_unsets'                    => true,
        'no_binary_string'                              => true,
        'no_superfluous_elseif'                         => true,
        'phpdoc_add_missing_param_annotation'           => true,
        'phpdoc_order'                                  => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types_order'                            => true,
        'phpdoc_align'                                  => false,
        'binary_operator_spaces'                        => [
            'default'   => 'single_space',
            'operators' => [
                '='  => 'align_single_space',
                '=>' => 'align_single_space',
            ],
        ],
        'concat_space'    => false,
        'new_with_braces' => false,

        'single_line_comment_spacing' => false,
    ])
    ->setFinder($finder);
