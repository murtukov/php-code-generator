<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->name('*.php')
    ->in([__DIR__.'/src', __DIR__.'/tests'])
;

$config = new PhpCsFixer\Config();

return $config->setRules([
        '@Symfony' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['const', 'class', 'function']
        ],
        'global_namespace_import' => ['import_functions' => true, 'import_classes' => true],
    ])
    ->setFinder($finder)
    ->setUsingCache(true);