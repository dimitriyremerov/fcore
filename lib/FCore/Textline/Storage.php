<?php
namespace FCore\Textline;
use FCore\Db\Mongo\Storage as MongoStorage;
use FCore\Textline;

class Storage extends MongoStorage
{
	private $lang;

	public function __construct($lang)
	{
		parent::__construct();
		$this->lang = $lang;
		$this->collectionName .= '_' . $lang;
	}

	private function loadByMapLang(array $textlineMap, $lang)
	{
		if (!$textlineMap) {
			return array();
		}
		$data = $this->load([
			'lang' => $lang,
			'textlineId' => ['$in' => array_keys($textlineMap)],
			'status' => 0,
		], ['textlineId', 'textline']);
		$textlines = [];
		foreach ($data as $row) {
			$textlines[$textlineMap[$row['textlineId']]] = $row['textline'];
		}
		return $textlines;
	}

	public function loadByTexts(array $texts)
	{
		$data = $this->load([
			'_id' => ['$in' => $texts],
		]);
		foreach ($texts as $text) {
			if (!isset($data[$text])) {
				$textline = new Textline();
				$textline->set_id($text);
				$this->save($textline);
				//$this->mongo->selectDB($this->dbName)->selectCollection($this->collectionName)->insert(array('_id' => $text));
			}
		}
		return $data;
	}
	
	/**
	 * @param array(int $txlId => string $txlRef) $textlineMap
	 */
	public function loadByMap(array $textlineMap)
	{
		$textlines = $this->loadByMapLang($textlineMap, $this->lang);
		$notLoadedTextlines = array_diff($textlineMap, array_keys($textlines));
		$textlines += $this->loadByMapLang($textlineMap, \FCore\Lang::LANG_DEFAULT);

		return $textlines;
	}
}
