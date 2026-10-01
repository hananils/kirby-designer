<?php return array(
    'root' => array(
        'name' => 'hananils/kirby-designer',
        'pretty_version' => '2.0.0',
        'version' => '2.0.0.0',
        'reference' => null,
        'type' => 'kirby-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'hananils/document' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => '9d3c01ca9d9eac2f9bc732e84856983e74ec2203',
            'type' => 'library',
            'install_path' => __DIR__ . '/../hananils/document',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'hananils/kirby-designer' => array(
            'pretty_version' => '2.0.0',
            'version' => '2.0.0.0',
            'reference' => null,
            'type' => 'kirby-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'hananils/kirby-fields' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'd32d18c330faa503dff54fc66e12ac9b98782749',
            'type' => 'helper',
            'install_path' => __DIR__ . '/../hananils/kirby-fields',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'hananils/kirby-plugins' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => '8a6379b185df611cf2acfe705182a14777b7e2c9',
            'type' => 'library',
            'install_path' => __DIR__ . '/../hananils/kirby-plugins',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
    ),
);
