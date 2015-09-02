<?php
namespace FCore\Page;

class Request
{
	private $get;
	private $post;
	private $cookie;
	private $ajax;

	private $order;
	
	public function __construct(array $get = null, array $post = null, array $cookie = null, $ajax = null)
	{
		$this->get = $get;
		$this->post = $post;
		$this->cookie = $cookie;
		$this->ajax = (bool) $ajax;
		$this->setRequestOrder('gpc');
	}
	/**
	 * @param string $order string of three symbols - g, p, c in any order
	 */
	public function setRequestOrder($order)
	{
		$containersMap = array(
			'g' => &$this->get,
			'p' => &$this->post,
			'c' => &$this->cookie,
		);
		$this->order = array();
		for ($i = 0; $i < strlen($order); $i++) {
			$container = $order{$i};
			if (isset($containersMap[$container])) {
				$this->order[] = &$containersMap[$container];
			}
		}
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		return is_array($this->get) && isset($this->get[$key]) ? $this->get[$key] : null;
	}
	
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function post($key)
	{
		return is_array($this->post) && isset($this->post[$key]) ? $this->post[$key] : null;
	}

	public function isAjax()
	{
		return $this->ajax;
	}
	
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function cookie($key)
	{
		return is_array($this->cookie) && isset($this->cookie[$key]) ? $this->cookie[$key] : null;
	}

	public function isGet()
	{
		return is_array($this->get);
	}
	public function isPost()
	{
		return is_array($this->post);
	}
	public function isCookie()
	{
		return is_array($this->cookie);
	}
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function request($key)
	{
		$order = $this->order;
		foreach ($order as $container) {
			if (is_array($container) && isset($container[$key])) {
				return $container[$key];
			}
		}
	}
}
