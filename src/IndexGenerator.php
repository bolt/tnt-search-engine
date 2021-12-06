<?php

namespace Bolt\TntSearch;

use Bolt\TntSearch\Doctrine\DbConnectionConfig;
use TeamTNT\TNTSearch\TNTSearch;

class IndexGenerator
{
    /** @var DbConnectionConfig */
    private $connectionConfig;

    public function __construct(DbConnectionConfig $connectionConfig)
    {
        $this->connectionConfig = $connectionConfig;
    }

    public function generate(): void
    {
        $tnt = new TNTSearch;

        $tnt->loadConfig($this->connectionConfig->getConfig());

        $indexer = $tnt->createIndex('records.index');

        // todo: doctrine query?
        $indexer->query('SELECT bolt_content.id, bolt_field_translation.value FROM bolt_content LEFT JOIN bolt_field on bolt_field.content_id = bolt_content.id LEFT JOIN bolt_field_translation on bolt_field_translation.translatable_id = bolt_field.id  WHERE bolt_field.name = "title"  LIMIT 100;');

        $indexer->run();
    }
}