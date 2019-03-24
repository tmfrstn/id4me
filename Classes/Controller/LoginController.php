<?php

namespace TmFrstn\Id4me\Controller;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Id4me\RP\Service;

class LoginController extends ActionController implements LoggerAwareInterface
{

    use LoggerAwareTrait;

    /**
     * @var \Id4me\RP\Service
     */
    private $id4Me = NULL;

    /**
     * UriBuilder
     *
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     * @inject
     */
    protected $uriBuilder = NULL;

    protected $identifier = 'idtest1.domainid.community';

    public function initializeAction()
    {
        $this->id4Me = new Service();
    }

    public function formAction()
    {
        $openIdConfig = $this->provideOpenIdConfig();

        $client = $this->id4Me->register(
            $openIdConfig,
            $this->identifier,
            sprintf('https://id4me.tmfrstn.de/login/', $this->identifier)
        );

        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($client);

        $authorizationUrl = $this->id4Me->getAuthorizationUrl(
            $openIdConfig,
            $client->getClientId(),
            $this->identifier,
            $client->getActiveRedirectUri(),
            'https://id4me.tmfrstn.de/'
        );

        $GLOBALS['TSFE']->fe_user->setKey("ses","clientId",$client->getClientId());
        $GLOBALS['TSFE']->fe_user->setKey("ses","clientSecret",$client->getClientSecret());
        $GLOBALS['TSFE']->storeSessionData();

        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($authorizationUrl);

        $this->redirectToURI($authorizationUrl);

    }

    public function authenticateAction()
    {

        $clientId = $GLOBALS['TSFE']->fe_user->getKey('ses', 'clientId');
        $clientSecret = $GLOBALS['TSFE']->fe_user->getKey('ses', 'clientSecret');

        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($clientId);
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($clientSecret);

        $openIdConfig = $this->provideOpenIdConfig();

        $accessTokens = $this->id4Me->getAccessTokens(
            $openIdConfig,
            'WU9ZOZGC0DgzS9blLECd',
            sprintf('https://id4me.tmfrstn.de/', $this->identifier),
            $clientId,
            $clientSecret
        );
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($accessTokens);
    }

    private function provideOpenIdConfig()
    {
        $authorityName = $this->id4Me->discover($this->identifier);
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($authorityName);

        $openIdConfig = $this->id4Me->getOpenIdConfig($authorityName);
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($openIdConfig);

        return $openIdConfig;
    }
}