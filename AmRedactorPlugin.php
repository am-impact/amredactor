<?php
namespace Craft;

class AmRedactorPlugin extends BasePlugin
{
    public function getName()
    {
         return 'a&m impact redactor';
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'a&m impact';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.am-impact.nl';
    }

    public function init()
    {
        if(craft()->request->isCpRequest())
        {
            craft()->templates->includeJsResource('amredactor/js/amredactor.js');
        }
    }
}