<?php

declare(strict_types=1);

namespace Bolt\TntSearch\Controller;

use Bolt\Controller\Backend\BackendZoneInterface;
use Bolt\Extension\ExtensionController;
use Bolt\TntSearch\IndexGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/%bolt.backend_url%/tnt-search")
 */
class GenerateController extends ExtensionController implements BackendZoneInterface
{
    /** @var IndexGenerator */
    private $generator;

    /**
     * @required
     */
    public function init(IndexGenerator $generator): void
    {
        $this->generator = $generator;
    }

    /**
     * @Route("/generate", name="tnt_search_generate_index")
     */
    public function index(): Response
    {
        $this->generator->generate();

        return $this->render('@tnt-search/generate-index.twig');
    }
}
