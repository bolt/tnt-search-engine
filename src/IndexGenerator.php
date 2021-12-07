<?php

declare(strict_types=1);

namespace Bolt\TntSearch;

use Bolt\Entity\Content;
use Doctrine\ORM\EntityManagerInterface;

class IndexGenerator
{
    /** @var TntEngine */
    private $engine;

    /** @var \Bolt\Configuration\Config */
    private $boltConfig;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(TntEngine $engine, \Bolt\Configuration\Config $boltConfig, EntityManagerInterface $em)
    {
        $this->engine = $engine;
        $this->boltConfig = $boltConfig;
        $this->em = $em;
    }

    public function generate(): void
    {
        $tnt = $this->engine->get();
        $index = $tnt->createIndex('records.index');
        $index->disableOutput = true;

        $index->query($this->getQuery());
        $index->disableOutput = true;

        // todo: makes the process a lot slower, but goes through all of them. Otherwise, the last page
        // with less items than the step size gets ommitted.
        $index->steps = 1;

        $index->run();
    }

    public function update(Content $content): void
    {
        $query = $this->getQuery();
        $whereClause = sprintf(' bolt_content.id = %d AND', $content->getId());
        $position = mb_strpos($query, 'WHERE') + 5;

        $query = substr_replace($query, $whereClause, $position, 0);
        $statement = $this->em->getConnection()->prepare($query);
        $statement->executeStatement();
        $results = $statement->fetchAll();

        $tnt = $this->engine->get();
        $tnt->selectIndex('records.index');
        $index = $tnt->getIndex();

        // Delete the current document, then add all new fields.
        $index->delete($content->getId());
        foreach ($results as $result) {
            $index->insert($result);
        }
    }

    protected function getQuery(): string
    {
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

        return sprintf($query, $contentTypes);
    }
}
