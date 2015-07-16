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
        return '1.1.0';
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
            craft()->templates->includeJs('window.amredactorClasses = ' . json_encode( $settings['classes'] ) . ';');
            craft()->templates->includeJsResource('amredactor/js/amredactor.js');
            craft()->templates->includeCssResource('amredactor/css/amredactor.css');
        }
    }

    public function prepSettings($settings)
    {
        if(!isset($settings['classes'])) {
            $settings['classes'] = array();
        }

        return $settings;
    }

    protected function defineSettings()
    {
        return array(
            'cssPath' => array(AttributeType::String, 'default' => '{submap}resources/css/redactor.css'),
            'classes' => array(AttributeType::Mixed, 'default' => array())
        );
    }
}