<?php

declare(strict_types=1);

namespace Bolt\TntSearch;

use Bolt\TntSearch\Doctrine\ConnectionConfig;
use TeamTNT\TNTSearch\TNTSearch;

class TntEngine
{
    /** @var ConnectionConfig */
    private $connectionConfig;

    /** @var TNTSearch|null */
    private $tnt;

    /** @var Config */
    private $config;

    public function __construct(ConnectionConfig $connectionConfig, Config $config)
    {
        $this->connectionConfig = $connectionConfig;
        $this->config = $config;
    }

    public function get(): TNTSearch
    {
        if (! $this->tnt) {
            $this->tnt = new TNTSearch();
            $this->tnt->loadConfig($this->connectionConfig->getConfig());

            $config = $this->config->getConfig();

            $this->tnt->fuzziness = $config['fuzzy']['enabled'] ?? false;
            $this->tnt->fuzzy_distance = $config['fuzzy']['distance'] ?? 2;
            $this->tnt->fuzzy_prefix_length = $config['fuzzy']['prefix_length'] ?? 2;
            $this->tnt->fuzzy_max_expansions = $config['fuzzy']['max_expansions'] ?? 50;
        }

        return $this->tnt;
    }
}
