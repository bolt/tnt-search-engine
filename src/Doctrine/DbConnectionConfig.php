<?php

namespace Bolt\TntSearch\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DbConnectionConfig
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var string */
    private $projectDir;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->projectDir = $container->get('kernel')->getProjectDir();
    }

    public function getConfig(): array
    {
        $connection = $this->em->getConnection();

        return [
            'driver'    => $connection->getDatabasePlatform()->getName(),
            'host'      => $connection->getHost(),
            'database'  => $connection->getDatabase(),
            'username'  => $connection->getUsername(),
            'password'  => $connection->getPassword(),
            'storage'   => sprintf('%s%s', $this->projectDir, '/var/data/')
        ];
    }
}