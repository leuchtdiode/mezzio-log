<?php
declare(strict_types=1);

namespace Log\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Rotate extends Command
{
	const DELIMITER = '---';

	public function __construct(
		private readonly array $config
	)
	{
		parent::__construct('log:rotate');
	}

	protected function configure(): void
	{
		$this->setDescription('Rotates log files');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$rotates = $this->config['log']['rotate'] ?? [];

		$time = time();

		foreach ($rotates as $rotateConfig)
		{
			$glob = $rotateConfig['glob'];

			foreach (glob($glob) as $path)
			{
				$tmpPath = sprintf('%s%s%s.tmp', $path, self::DELIMITER, $time);
				$newPath = sprintf('%s%s%s.gz', $path, self::DELIMITER, $time);

				rename($path, $tmpPath);

				if ($fpOut = gzopen($newPath, 'wb' . ($rotateConfig['gzLevel'] ?? 9)))
				{
					if ($fpIn = fopen($tmpPath, 'rb'))
					{
						while (!feof($fpIn))
						{
							gzwrite($fpOut, fread($fpIn, 1024 * 512));
						}

						fclose($fpIn);
					}

					gzclose($fpOut);
				}

				unlink($tmpPath);
			}

			$maxTimestamp = $time - ($rotateConfig['hoursToKeep'] * 60 * 60);

			foreach (glob($glob . self::DELIMITER . '*.gz') as $rotatedPath)
			{
				$boom = explode(self::DELIMITER, $rotatedPath);

				$createdTimestamp = str_replace('.gz', '', $boom[count($boom) - 1]);

				if ($createdTimestamp < $maxTimestamp)
				{
					unlink($rotatedPath);
				}
			}
		}

		return self::SUCCESS;
	}
}