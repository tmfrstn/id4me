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

#    protected $identifier = 'idtest1.domainid.community';
    protected $identifier = 'eisenmann.io';

    public function initializeAction()
    {
        $this->id4Me = new Service();
    }

    public function formAction()
    {
        if(!isset($_GET['autheticate']) || $_GET['autheticate'] != 'yes') {
            return $this->view->render();
        }

        $openIdConfig = $this->provideOpenIdConfig();

        $client = $this->id4Me->register(
            $openIdConfig,
            $this->identifier,
            sprintf('https://id4me.tmfrstn.de/login/', $this->identifier)
        );

        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($client,'Client');

        $uniqId = uniqid();

        $authorizationUrl = $this->id4Me->getAuthorizationUrl(
            $openIdConfig,
            $client->getClientId(),
            $this->identifier,
            $client->getActiveRedirectUri(),
            $uniqId
        );

        $GLOBALS['TSFE']->fe_user->setKey("ses","clientId",$client->getClientId());
        $GLOBALS['TSFE']->fe_user->setKey("ses","clientSecret",$client->getClientSecret());
        $GLOBALS['TSFE']->fe_user->setKey("ses","uniqId",$uniqId);
        $GLOBALS['TSFE']->storeSessionData();

        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($authorizationUrl,'authorizationUrl');

        $this->redirectToURI($authorizationUrl);

    }

    public function authenticateAction()
    {

        $code = $_GET['code'];
        $clientId = $GLOBALS['TSFE']->fe_user->getKey('ses', 'clientId');
        $clientSecret = $GLOBALS['TSFE']->fe_user->getKey('ses', 'clientSecret');
        $uniqId = $GLOBALS['TSFE']->fe_user->getKey('ses', 'uniqId');

        if($uniqId != $_GET['state']){
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('NO');
        } else {
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('YES');
        }
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($clientId, 'clientId');
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($clientSecret, 'clientSecret');
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($code, 'code');

        $openIdConfig = $this->provideOpenIdConfig();

        $accessTokens = $this->id4Me->getAccessTokens(
            $openIdConfig,
            $code,
            sprintf('https://id4me.tmfrstn.de/login/', $this->identifier),
            $clientId,
            $clientSecret
        );
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($accessTokens, 'accessTokens');
    }

    private function provideOpenIdConfig()
    {
        $authorityName = $this->id4Me->discover($this->identifier);
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($authorityName, 'authorityName');

        $openIdConfig = $this->id4Me->getOpenIdConfig($authorityName);
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($openIdConfig, 'openIdConfig');

        return $openIdConfig;
    }
}