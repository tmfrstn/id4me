<?php

namespace TmFrstn\Id4me\Controller;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class LoginController extends ActionController implements LoggerAwareInterface
{

    use LoggerAwareTrait;


    public function formAction()
    {

    }

}