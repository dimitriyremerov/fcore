<?php
namespace FCore\Runnable;

class Operation extends \FCore\Entity
{
	/**
	 * @var string
	 */
	protected $name;
	/**
	 * @var string
	 */
	protected $lang;
	/**
	 * @var string
	 */
	protected $translation;
	
	/**
	 * @return the $name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return the $lang
	 */
	public function getLang()
	{
		return $this->lang;
	}

	/**
	 * @return the $textlineId
	 */
	public function getTranslation()
	{
		return $this->translation;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @param field_type $lang
	 */
	public function setLang($lang)
	{
		$this->lang = $lang;
	}

	/**
	 * @param field_type $textlineId
	 */
	public function setTranslation($translation)
	{
		$this->translation = $translation;
	}
}
