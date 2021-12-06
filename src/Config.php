<?php

declare(strict_types=1);

namespace Bolt\TntSearch;

use Bolt\Extension\ExtensionRegistry;

class Config
{
    /** @var */
    private $config;

    /** @var ExtensionRegistry */
    private $registry;

    /** @var Extension|null */
    private $extension;

    public function __construct(ExtensionRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getRedirects(): array
    {
        return $this->getConfig()['redirects'] ?? [];
    }

    public function getConfig(): array
    {
        if ($this->config) {
            return $this->config;
        }

        $extension = $this->getExtension();

        $this->config = $extension->getConfig()->toArray();

        return $this->config;
    }

    private function getExtension()
    {
        if (! $this->extension) {
            $this->extension = $this->registry->getExtension(Extension::class);
        }

        return $this->extension;
    }
}
