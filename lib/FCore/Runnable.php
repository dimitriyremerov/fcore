<?php

namespace FCore;

interface Runnable
{
	/**
	 * @return Runnable
	 */
	public function execute();
	/**
	 * @return string
	 */
	public function render();
}
