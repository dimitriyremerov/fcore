<?php
namespace FCore\Twig\Extensions;

interface Preparseable
{
	public function preParse($templateName);
}
