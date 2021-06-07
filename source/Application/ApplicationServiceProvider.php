<?php

declare(strict_types=1);

namespace Atlas\DDD\Application;

use Psr\Container\ContainerInterface;
use Fence\View;
use Fence\SessionManager;
use Atlas\DDD\Application\UserInterface\IndexPage;
use Atlas\DDD\Application\UserInterface\Institutes\InstitutesRegistrationPage;

final class ApplicationServiceProvider
{
    public static function getDefinitions(): array
    {
        return [

            'user' => SessionManager::getUser(),

            'templates' => dirname(__DIR__) . '/resources/templates',

            'views' => dirname(__DIR__) . '/resources/views',

            View::class => function (ContainerInterface $c) {
                return new View($c->get('templates'));
            },

            IndexPage::class => function (ContainerInterface $c) {
                return new IndexPage($c->get('views') . '/login.json', $c->get(View::class));
            },
            InstitutesRegistrationPage::class => function (ContainerInterface $c) {
                return new InstitutesRegistrationPage($c->get('views') . '/login.json', $c->get(View::class));
            }
        ];
    }
}