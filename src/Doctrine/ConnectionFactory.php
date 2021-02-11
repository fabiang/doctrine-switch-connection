<?php

namespace Fabiang\Common\SwitchDatabase\Doctrine;

use DoctrineORMModule\Service\DBALConnectionFactory;
use Interop\Container\ContainerInterface;

class ConnectionFactory extends DBALConnectionFactory
{

    /**
     * Set name to null by default
     */
    public function __construct()
    {
        parent::__construct(null);
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $name       = substr($requestedName, strrpos($requestedName, '.') + 1);
        $this->name = $name;
        return parent::__invoke($container, $requestedName, $options);
    }

}
