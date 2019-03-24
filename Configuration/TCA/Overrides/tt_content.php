<?php
defined('TYPO3_MODE') or die();

/***************
 * Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'id4me',
    'Pi1',
    'LLL:EXT:id4me/Resources/Private/Language/locallang_be.xlf:pi1_title'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['id4me_pi1'] = 'recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['id4me_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('id4me_pi1',
    'FILE:EXT:id4me/Configuration/FlexForms/flexform_login.xml');