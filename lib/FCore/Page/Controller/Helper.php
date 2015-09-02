<?php
namespace FCore\Page\Controller;

class Helper
{
	/**
	 * @param array $postFields
	 */
	public static function createFormData(array $postFieldsDefault, \FCore\Page\Request $pageRequest)
	{
		$formData = array();
		foreach ($postFieldsDefault as $postFieldKey => $postFieldDefault) {
			$formData[$postFieldKey] = $pageRequest->post($postFieldKey) ?: $postFieldDefault;
		}
		return $formData;
	}
}
