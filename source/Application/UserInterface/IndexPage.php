<?php

declare(strict_types=1);

namespace Atlas\DDD\Application\UserInterface;

use Fence\Content;
use Fence\Logger;
use Fence\View\View;

final class IndexPage extends Content
{
    /** @var View */
    private $templateEngine;

    public function __construct(
        $filename,
        View $templateEngine
    ) {
        parent::__construct($filename);

        $this->templateEngine = $templateEngine;
    }

    protected function validate($jreader)
    {
        return true;
    }

    protected function init()
    {
    }

    public function __invoke()
    {
        return $this->render();
    }

    public function render()
    {
        echo $this->templateEngine->render('base.html.twig');
    }
}
