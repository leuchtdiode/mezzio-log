<?php
namespace Log;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;

class LoggerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Logger
	{
		$config = $container->get('config');

		$logger = new Logger('application');

		foreach ($config['log']['files'] as $fileInfo)
		{
			if (!$fileInfo['enabled'])
			{
				continue;
			}

			$handler = new StreamHandler($fileInfo['path'], $fileInfo['logLevel']);

			if (($formatter = $fileInfo['formatter'] ?? null))
			{
				$handler->setFormatter(new $formatter('application'));
			}

			$logger->pushHandler($handler);
		}

		return $logger;
	}
}