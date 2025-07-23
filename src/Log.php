<?php
namespace Log;

use Psr\Log\LoggerInterface;

class Log
{
	private static LoggerInterface $logger;

	public static function setLogger(LoggerInterface $logger): void
	{
		self::$logger = $logger;
	}

	public static function getLogger(): LoggerInterface
	{
		return self::$logger;
	}

	public static function debug($text): void
	{
		self::$logger->debug(
			self::prepare($text)
		);
	}

	public static function info($text): void
	{
		self::$logger->info(
			self::prepare($text)
		);
	}

	public static function warn($text): void
	{
		self::$logger->warning(
			self::prepare($text)
		);
	}

	public static function error($text): void
	{
		self::$logger->error(
			self::prepare($text)
		);
	}

	private static function prepare($text): string
	{
		if (is_array($text))
		{
			return var_export($text, true);
		}

		return $text;
	}
}