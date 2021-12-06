<?php

declare(strict_types=1);

namespace Bolt\TntSearch;

class IndexGenerator
{
    /** @var TntEngine */
    private $engine;

    /** @var \Bolt\Configuration\Config */
    private $boltConfig;

    public function __construct(TntEngine $engine, \Bolt\Configuration\Config $boltConfig)
    {
        $this->engine = $engine;
        $this->boltConfig = $boltConfig;
    }

    public function generate(): void
    {
        $tnt = $this->engine->get();

        $indexer = $tnt->createIndex('records.index');

        $contentTypes = $this->boltConfig->get('contenttypes')
            ->where('searchable', true)
            ->keys()
            ->map(function (string $ct) {
                return sprintf("'%s'", $ct);
            })->join(',');

        $query = <<<'EOD'
                SELECT bolt_content.id, bolt_field_translation.value
                FROM bolt_content
                LEFT JOIN bolt_field ON bolt_field.content_id = bolt_content.id
                LEFT JOIN bolt_field_translation ON bolt_field_translation.translatable_id = bolt_field.id
                WHERE bolt_content.content_type IN (%s);
EOD;

        $indexer->query(sprintf($query, $contentTypes));
        $indexer->disableOutput = true;

        // todo: makes the process a lot slower, but goes through all of them. Otherwise, the last page
        // with less items than the step size gets ommitted.
        $indexer->steps = 1;

        $indexer->run();
    }
}
