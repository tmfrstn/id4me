<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Id4me.org auth',
    'description' => 'Authentication via ID4me.org',
    'category' => 'fe',
    'author' => 'Cloudfest Hackathon Team',
    'author_email' => 'feuerstein.rhp@gmail.com',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '0.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];