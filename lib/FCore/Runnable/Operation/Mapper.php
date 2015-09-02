<?php
namespace FCore\Runnable\Operation;
use FCore\Runnable\Operation;
/**
 * @author Dimitriy
 * @todo Improve work with storages 
 */
class Mapper
{
	const FIELD_LANG = 'lang';
	const FIELD_TRANSLATION = 'translation';
	const FIELD_OPERATION_NAME = 'name';

	private $lang;
	
	public function __construct($lang)
	{
		$this->lang = (string) $lang;
	}

	public function getLang()
	{
		return $this->lang;
	}
	
	protected function createStorage()
	{
		return new Storage();
	}
	
	public function mapOperations($operationTranslations)
	{
		if ($this->lang == \FCore\Lang::LANG_DEFAULT) {
			return array_combine($operationTranslations, $operationTranslations);
		}
		$storage = $this->createStorage();
		$operations = $storage->load([
			self::FIELD_LANG => $this->lang,
			self::FIELD_TRANSLATION => ['$in' => $operationTranslations]
		]);
		$return = [];
		foreach ($operations as $operation) {
			/* @var $operation Operation */
			$return[$operation->getTranslation()] = $operation->getName();
		} 
		return $return;
	}
	
	public function mapOperation($operationTranslation)
	{
		$operations = $this->mapOperations(array($operationTranslation));
		return isset($operations[$operationTranslation]) ? $operations[$operationTranslation] : $operationTranslation;
	}
	
	public function unmapOperation($operationName)
	{
		$operations = $this->unmapOperations([$operationName]);
		return isset($operations[$operationName]) ? $operations[$operationName] : $operationName;
	}
	
	public function unmapOperations($operationNames)
	{
		if ($this->lang == \FCore\Lang::LANG_DEFAULT) {
			return array_combine($operationNames, $operationNames);
		}
		$storage = $this->createStorage();
		$operations = $storage->load([
			self::FIELD_LANG => $this->lang,
			self::FIELD_OPERATION_NAME => ['$in' => $operationNames]
		]);
		$return = [];
		foreach ($operations as $operation) {
			/* @var $operation Operation */
			$return[$operation->getName()] = $operation->getTranslation();
		}
		return $return;
	}
}
