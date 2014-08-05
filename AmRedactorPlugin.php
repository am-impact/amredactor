<?php
namespace Craft;

class AmRedactorPlugin extends BasePlugin
{
<<<<<<< Updated upstream
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
=======
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
		if(craft()->request->isCpRequest() && craft()->userSession->isAdmin())
		{
			craft()->templates->includeJsResource('amredactor/js/amredactor.js');
		}

		if (craft()->request->isPostRequest() && craft()->request->isCpRequest())
		{
			$this->transformImages();
		}
	}

	private function transformImages()
	{
		craft()->on('entries.beforeSaveEntry', function(Event $event)
		{
			$entry = $event->params['entry'];
			foreach ($entry->getFieldLayout()->getFields() as $fieldLayout)
			{
				$field = $fieldLayout->getField();
				$fieldType = $field->getFieldType();
				if ($fieldType instanceof RichTextFieldType)
				{
					$richText = $entry->getContent()->getAttribute($field->handle);
					$doc = new \DOMDocument();
					libxml_use_internal_errors(true);
					$doc->loadHTML($richText);
					$images = $doc->getElementsByTagName('img');

					foreach ($images as $image)
					{
						$assetPartsString = substr($image->getAttribute('src'), 1, count($image->getAttribute('src')) - 2);
						$sourceParts = explode(':', $assetPartsString);
						$dimensions = craft()->amRedactor->getDimensionsFromStyle($image->getAttribute('style'));
						$transformWithoutDimensions = ($sourceParts[2] != 'url' && $dimensions['height'] == 'auto' && $dimensions['width'] == 'auto');

						// Don't do anything if a transform is already set, but no dimensions were specified.
						if (!$transformWithoutDimensions)
						{
							$assetTransformHandle = craft()->amRedactor->getNearestTransform($dimensions);

							if ($assetTransformHandle !== false)
							{
								$sourceParts[2] = $assetTransformHandle;
							}
							$image->setAttribute('src', '{' . implode(':', $sourceParts) . '}');
						}
					}

					$entry->getContent()->setAttribute($field->handle, urldecode($doc->saveHTML()));
				}
			}
		});
	}
>>>>>>> Stashed changes
}