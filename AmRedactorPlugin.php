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
        return '1.0.2';
    }

    public function getDeveloper()
    {
        return 'a&m impact';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.am-impact.nl';
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('amredactor/settings', array(
            'settings' => $this->getSettings()
        ));
    }

    public function init()
    {
        if(craft()->request->isCpRequest() && ! craft()->request->isAjaxRequest())
        {
            $settings = $this->getSettings();
            $redactorCss = craft()->config->parseEnvironmentString($settings['cssPath']);

            craft()->templates->includeCssFile( $redactorCss );
            craft()->templates->includeJsResource('amredactor/js/amredactor.js');
        }
    }

    protected function defineSettings()
    {
        return array(
            'cssPath' => array(AttributeType::String, 'default' => '{submap}resources/compiled/redactor.css')
        );
    }
}