<?php

namespace AppModule\Service;

class RenderService implements \Hill\IInjectable
{
    private $view;
    public function __construct(\AppModule\Service\ConfigService $configService)
    {
        $options = $configService->getOptions();
        $this->view = new \Hill\View(
            $options['rootDir'] . "/views"
        );
    }

    public function render($file, array $params = [])
    {
        return $this->view->fetch($file, $params);
    }
}
