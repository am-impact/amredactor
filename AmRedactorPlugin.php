<?php
namespace Craft;

class AmRedactorPlugin extends BasePlugin
{
    public function getName()
    {
         return 'a&m redactor';
    }

    public function getVersion()
    {
        return '1.1.3';
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
        if (craft()->request->isCpRequest() && craft()->userSession->isLoggedIn() && ! craft()->request->isAjaxRequest()) {
            $settings = $this->getSettings();
            $redactorCss = craft()->config->parseEnvironmentString($settings['cssPath']);

            // Find out if we have to show the HTML button in redactor
            $showHtmlButton = false;
            $user = craft()->userSession->getUser();
            if ($user) {
                if ($user->admin) {
                    $showHtmlButton = true;
                }
                elseif (isset($settings['showHtmlButtonForUserGroups']) && ! empty($settings['showHtmlButtonForUserGroups'])) {
                    foreach ($settings['showHtmlButtonForUserGroups'] as $userGroupId) {
                        if ($user->isInGroup($userGroupId)) {
                            $showHtmlButton = true;
                            break;
                        }
                    }
                }
            }

            // Include our redactor plugin
            craft()->templates->includeCssFile( $redactorCss );
            craft()->templates->includeJs('window.amredactorClasses = ' . json_encode( $settings['classes'] ) . ';');
            craft()->templates->includeJs('window.amredactorShowHtmlButton = ' . ($showHtmlButton ? '"y"' : '"n"') . ';');
            craft()->templates->includeJsResource('amredactor/js/amredactor.js');
            craft()->templates->includeCssResource('amredactor/css/amredactor.css');

            // Don't let task bash requests
            craft()->templates->includeJs('Craft.CP.taskTrackerUpdateInterval = 60000;');
            craft()->templates->includeJs('Craft.CP.taskTrackerHudUpdateInterval = 60000;');
        }
    }

    public function prepSettings($settings)
    {
        if (! isset($settings['classes'])) {
            $settings['classes'] = array();
        }

        return $settings;
    }

    protected function defineSettings()
    {
        return array(
            'cssPath' => array(AttributeType::String, 'default' => '{submap}resources/css/redactor.css'),
            'classes' => array(AttributeType::Mixed, 'default' => array()),
            'showHtmlButtonForUserGroups' => array(AttributeType::Mixed)
        );
    }
}
