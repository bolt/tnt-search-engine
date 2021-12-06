<?php

declare(strict_types=1);

namespace Bolt\TntSearch\Controller;

use Bolt\Controller\Frontend\FrontendZoneInterface;
use Bolt\Controller\TwigAwareController;
use Bolt\Repository\ContentRepository;
use Bolt\TntSearch\TntEngine;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends TwigAwareController implements FrontendZoneInterface
{
    /** @var TntEngine */
    private $engine;

    /** @var ContentRepository */
    private $repository;

    /**
     * @required
     */
    public function init(TntEngine $engine, ContentRepository $repository): void
    {
        $this->engine = $engine;
        $this->repository = $repository;
    }

    /**
     * @Route("/search", methods={"GET|POST"}, name="tnt_search")
     * @Route("/{_locale}/search", methods={"GET|POST"}, name="tnt_search_locale")
     */
    public function search(): Response
    {
        $page = (int) $this->getFromRequest('page', '1');
        $searchTerm = $this->getFromRequestArray(['searchTerm', 'search', 'q'], '');
        $amountPerPage = (int) $this->config->get('general/listing_records');

        $tnt = $this->engine->get();
        $tnt->selectIndex('records.index');

        $results = $tnt->search($searchTerm, 10000);
        $records = $this->repository->findBy(['id' => $results['ids']]);
        $records = new Pagerfanta(new ArrayAdapter($records));
        $records->setMaxPerPage($amountPerPage);
        $records->setCurrentPage($page);

        $context = [
            'searchTerm' => $searchTerm,
            // Keep 'search' for Backwards Compatibility
            'search' => $searchTerm,
            'records' => $records,
        ];

        $templates = $this->templateChooser->forSearch();

        return $this->render($templates, $context);
    }
}
