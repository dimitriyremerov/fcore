<?php

namespace FCore;

class Application
{
    /**
     * @var User\Storage
     */
    protected $userStorage;

    /**
     * @param User\Storage $storage
     */
    public function setUserStorage(User\Storage $storage)
    {
        $this->userStorage = $storage;
    }

    /**
     * @return User\Storage
     */
    protected function getUserStorage() : User\Storage
    {
        return $this->userStorage ?? new User\Storage();
    }

    /**
     * @return string
     */
    public function run() : string
    {
        $pageRequest = new Page\Request();
        $request =  explode('/', $pageRequest->getUri());
        $session = new Session();

        $userAuthManager = new User\AuthManager($session, $this->getUserStorage());

        $user = $userAuthManager->authUser();

        $lang = \Zakaz\Lang::LANG_DEFAULT;
        if (!empty($request[1])) {
            $lang = $request[1];
            if (!\Zakaz\Lang::validateLang($lang)) {
                $lang = \Zakaz\Lang::LANG_DEFAULT;
            }
        }

        $operationMapper = new \Zakaz\Runnable\Operation\Mapper($lang);
        if (empty($request[2])) {
            $operation = 'index';
        } else {
            $operationTranslation = urldecode($request[2]);
            $operation = $operationMapper->mapOperation($operationTranslation);
            $operation = str_replace(['/', '-'], ['', '_'], $operation);
        }
        // TODO i18n operation

        $params = array_slice($request, 3);
        try {
            $controller = \Zakaz\Runnable\Factory::create($operation, $lang, $pageRequest, $user, $params);
            if ($controller instanceof \FCore\Page\Element\Controller) {
                $controller->addExtensions([
                    new \Twig_Extension_Debug(),
                    new \FCore\Twig\Extensions\General(),
                    new \FCore\Twig\Extensions\LinkMapper($operationMapper),
                    new \FCore\Twig\Extensions\I18n(new \Zakaz\Textline\Storage($lang)),
                ]);
            }
            $data = $controller->execute()->render();
        } catch (\Zakaz\Page\Controller\Factory\Exception $exc) {
            $data = $exc->getMessage();
        } catch (\Error $exc) {
            $data = $exc->getMessage();
        }

        return $data;
    }
}
