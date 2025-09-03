<?php
namespace Log;

use Log\Command\Rotate;
use Log\Console\GlobalLogInitializer;
use Psr\Log\LogLevel;

return [

	'dependencies' => [
		'abstract_factories' => [
			DefaultFactory::class,
		],
	],

	'log' => [
		'files' => [
			'main'  => [
				'enabled'  => true,
				'path'     => 'data/log/application.log',
				'logLevel' => LogLevel::DEBUG,
			],
			'error' => [
				'enabled'  => true,
				'path'     => 'data/log/error.log',
				'logLevel' => LogLevel::ERROR,
			],
		],
	],

	'console' => [
		'commands'     => [
			Rotate::class,
		],
		'initializers' => [
			GlobalLogInitializer::class,
		],
	],
];