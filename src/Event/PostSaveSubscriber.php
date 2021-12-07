<?php

declare(strict_types=1);

namespace Bolt\TntSearch\Event;

use Bolt\Event\ContentEvent;
use Bolt\TntSearch\IndexGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PostSaveSubscriber implements EventSubscriberInterface
{
    /** @var IndexGenerator */
    private $generator;

    public function __construct(IndexGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function onPostSave(ContentEvent $event): void
    {
        $content = $event->getContent();
        $this->generator->update($content);
    }

    public static function getSubscribedEvents()
    {
        return [
            ContentEvent::POST_SAVE => [['onPostSave']],
        ];
    }
}
