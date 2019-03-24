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
    private $id4Me = null;

    private $requestFactory = null;

    public function initializeAction()
    {
        /** @var \TYPO3\CMS\Core\Http\RequestFactory $requestFactory */
        $this->requestFactory = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Http\RequestFactory::class);
        $this->id4Me = new Service();
    }

    public function formAction()
    {
        $identifier = 'idtemp2.id4me.family';
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($identifier);
        $authorityName = $this->id4Me->discover($identifier);
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($authorityName);
        $openIdConfig = $this->id4Me->getOpenIdConfig($authorityName);
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($openIdConfig);
        $client = $this->id4Me->register(
            $openIdConfig,
            $identifier,
            sprintf('http://www.rezepte-elster.de/id4me.php', $identifier)
        );
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($client);
        $authorizationUrl = $this->id4Me->getAuthorizationUrl(
            $openIdConfig, $client->getClientId(), $identifier, $client->getActiveRedirectUri(), 'idtemp2.id4me.family'
        );
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($authorizationUrl);
        $accessTokens = $this->id4Me->getAccessTokens(
            $openIdConfig,
            readline('code:'),
            sprintf('http://www.rezepte-elster.de/id4me.php', $identifier),
            $client->getClientId(),
            $client->getClientSecret()
        );
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($accessTokens);

    }

}