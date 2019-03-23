<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'id4me',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:id4me/Resources/Public/Icons/Extension.png']
        );
    }
);