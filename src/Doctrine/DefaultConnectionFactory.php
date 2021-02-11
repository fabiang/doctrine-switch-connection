<?php

namespace Fabiang\Common\SwitchDatabase\Doctrine;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Fabiang\Common\SwitchDatabase\Doctrine\Exception\BadMethodCallException;
use Fabiang\Common\SwitchDatabase\Doctrine\Exception\UnexpectedValueException;
use Fabiang\Common\SwitchDatabase\Doctrine\Exception\RuntimeException;

final class DefaultConnectionFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return \DoctrineORMModule\Options\DBALConnection
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $globalConfig = $container->get('config');
        $config       = $globalConfig['switch-database'];

        $connectionName = $this->getConnection(
            $container->get($config['session_service']['name']),
            $config['session_service']['key']
        );

        if (null !== $connectionName) {
            $connectionMapping = $config['connection_mapping'];
            $connectionName    = $connectionMapping[$connectionName];
        } else {
            $connectionName = $config['default_connection'];
        }

        if (getenv('DOCTRINE_CONNECTION')) {
            $connectionName = getenv('DOCTRINE_CONNECTION');
        }

        $connection = $container->get('doctrine.connection.' . $connectionName);
        return $connection;
    }

    /**
     * @param object $service
     * @param array $config
     * @return string
     * @throws BadMethodCallException
     * @throws RuntimeException
     * @throws UnexpectedValueException
     */
    private function getConnection($service, array $config)
    {
        if (!is_object($service)) {
            throw new BadMethodCallException(sprintf(
                        "'%s' expects argument #1 to be object, but %s given",
                        __METHOD__,
                        gettype($service)
            ));
        }

        $name = $config['name'];
        switch ($config['type']) {
            case 'property':
                return isset($service->{$name}) ? $service->{$name} : null;
            case 'method':
                if (!method_exists($service, $name)) {
                    throw new RuntimeException(sprintf(
                                "Method '%s' doesn't exists on object '%s'",
                                $name,
                                get_class($service)
                    ));
                }

                return $service->{$name}();
            default:
                throw new UnexpectedValueException(
                        "Unexpected type of switch-database.session_service.key.type"
                        . " in your config, we only support 'property' and 'method"
                );
        }
    }

}
