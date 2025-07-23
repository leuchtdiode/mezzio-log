<?php
declare(strict_types=1);

namespace Log\Middleware;

use Log\Log;
use Log\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GlobalLogMiddleware implements MiddlewareInterface
{
	public function __construct(
		private readonly Logger $logger
	)
	{
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		Log::setLogger($this->logger);

		return $handler->handle($request);
	}
}