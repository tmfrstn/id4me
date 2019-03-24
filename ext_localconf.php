<?php
defined('TYPO3_MODE') or die();

$boot = function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'TmFrstn.id4me',
        'Pi1',
        [
            'Login' => 'form,authenticate',
        ],
        [
            'Login' => 'form,authenticate',
        ]
    );

    $highestPriority = 0;
    if (is_array($GLOBALS['T3_SERVICES']['auth'])) {
        foreach ($GLOBALS['T3_SERVICES']['auth'] as $service) {
            if ($service['priority'] > $highestPriority) {
                $highestPriority = $service['priority'];
            }
        }
    }

    $subtypes = 'authUserFE,getUserFE';
    $overrulingPriority = $highestPriority + 10;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        'id4me',
        'auth',
        \TmFrstn\Id4me\Service\AuthenticationService::class,
        [
            'title' => 'ID4me Authentication',
            'description' => 'Authenticates with ID4me',
            'subtype' => $subtypes,
            'available' => true,
            'priority' => $overrulingPriority,
            'quality' => $overrulingPriority,
            'os' => '',
            'exec' => '',
            'className' => \TmFrstn\Id4me\Service\AuthenticationService::class
        ]
    );

};

$boot();
unset($boot);