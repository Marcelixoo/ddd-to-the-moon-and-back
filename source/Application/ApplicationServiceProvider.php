<?php

declare(strict_types=1);

namespace Atlas\DDD\Application;

use Fence\View;
use Fence\SessionManager;
use Psr\Container\ContainerInterface;
use Atlas\DDD\Application\ProfileLinkGenerator\ProfileLinkGenerator;
use Atlas\DDD\Application\ProfileLinkGenerator\ProfileLinkGeneratorInterface;
use Atlas\DDD\Application\Pages\IndexPage;
use Atlas\DDD\Application\Pages\Institutes\InstitutesRegistrationPage;

final class ApplicationServiceProvider
{
    public static function getDefinitions(): array
    {
        return [

            'user' => SessionManager::getUser(),

            'templates' => function(ContainerInterface $c) {
                return $c->get('root_path') . '/resources/templates';
            },

            'views' => function(ContainerInterface $c) {
                return $c->get('root_path') . '/resources/views';
            },

            'uri' => getenv("REQUEST_URI"),

            View::class => function (ContainerInterface $c) {
                return new View($c->get('templates'));
            },

            IndexPage::class => function (ContainerInterface $c) {
                return new IndexPage($c->get('views') . '/login.json', $c->get(View::class));
            },
            InstitutesRegistrationPage::class => function (ContainerInterface $c) {
                return new InstitutesRegistrationPage($c->get('views') . '/login.json', $c->get(View::class));
            },
            ProfileLinkGeneratorInterface::class => function (ContainerInterface $c) {
                return new ProfileLinkGenerator($c->get('uri'));
            }
        ];
    }
}