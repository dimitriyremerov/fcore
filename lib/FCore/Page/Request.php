<?php
namespace FCore\Page;

class Request
{
    private $uri;
    private $get;
	private $post;
	private $cookie;
	private $ajax;

	private $order;
	
	public function __construct(array $get = null, array $post = null, array $cookie = null, bool $ajax = null, string $uri = null)
    {
		$this->get = $get ?? $_GET;
		$this->post = $post ?? $_POST ?? null;
		$this->cookie = $cookie ?? $_COOKIE ?? null;
		$this->ajax = $ajax ?? (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') == 'xmlhttprequest');
        $this->uri = $uri ?? $_SERVER['REQUEST_URI'];
		$this->setRequestOrder('gpc');
	}
	/**
	 * @param string $order string of three symbols - g, p, c in any order
	 */
	public function setRequestOrder(string $order)
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
	public function get(string $key) : string
	{
        return $this->get[$key] ?? '';
	}
	
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function post(string $key)
	{
		return is_array($this->post) && isset($this->post[$key]) ? $this->post[$key] : null;
	}

	public function isAjax() : bool
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

	public function isGet() : bool
	{
		return is_array($this->get);
	}
	public function isPost() : bool
	{
		return is_array($this->post);
	}
	public function isCookie() : bool
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

    /**
     * @return mixed
     */
    public function getUri() : string
    {
        return $this->uri;
    }
}
