<?php

declare(strict_types=1);

namespace Bolt\TntSearch\Menu;

use Bolt\Menu\ExtensionBackendMenuInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TntBackendMenu implements ExtensionBackendMenuInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function addItems(MenuItem $menu): void
    {
        $menu->addChild('TNT Search', [
            'extras' => [
                'type' => 'separator',
            ],
        ]);

        $menu->addChild('Generate index', [
            'uri' => $this->urlGenerator->generate('tnt_search_generate_index'),
            'extras' => [
                'icon' => 'fa-database',
            ],
        ]);
    }
}
