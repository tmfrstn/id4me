<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        if (TYPO3_MODE === 'BE') {
            $icons = [
                'ext-id4me-extension' => 'Extension.png',
            ];

            $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
            foreach ($icons as $identifier => $path) {
                $iconRegistry->registerIcon(
                    $identifier,
                    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                    ['source' => 'EXT:news/Resources/Public/Icons/' . $path]
                );
            }
        }
    }
);