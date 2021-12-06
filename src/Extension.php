<?php

declare(strict_types=1);

namespace Bolt\TntSearch;

use Bolt\Extension\BaseExtension;

class Extension extends BaseExtension
{
    /**
     * Return the full name of the extension
     */
    public function getName(): string
    {
        return 'Bolt TNTSearch Engine';
    }

    /**
     * Ran automatically, if the current request is in a browser.
     * You can use this method to set up things in your extension.
     *
     * Note: This runs on every request. Make sure what happens here is quick
     * and efficient.
     */
    public function initialize($cli = false): void
    {
        $this->addTwigNamespace('tnt-search');
    }

    /**
     * Ran automatically, if the current request is from the command line (CLI).
     * You can use this method to set up things in your extension.
     *
     * Note: This runs on every request. Make sure what happens here is quick
     * and efficient.
     */
    public function initializeCli(): void
    {
    }
}
