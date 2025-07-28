<?php
declare(strict_types=1);

namespace Log\Console;

use Log\Log;
use Log\Logger;

class GlobalLogInitializer
{
	public function __construct(
		private readonly Logger $logger
	)
	{
	}

	public function __invoke(): void
	{
		Log::setLogger($this->logger);
	}
}