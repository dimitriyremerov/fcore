<?php
/**
 * Created by PhpStorm.
 * User: Dimitriy
 * Date: 04/09/15
 * Time: 18:28
 */

namespace FCore;


use FCore\Page\Controller;

class Router
{

    /**
     * @var Page\Request
     */
    protected $pageRequest;

    /**
     * @param Page\Request $pageRequest
     */
    public function __construct(Page\Request $pageRequest)
    {
        $this->pageRequest = $pageRequest;
    }

    public function route() : Controller
    {
        $request =  explode('/', $this->pageRequest->getUri());

    }
}