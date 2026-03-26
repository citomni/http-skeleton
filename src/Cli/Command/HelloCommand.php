<?php
declare(strict_types=1);

namespace App\Cli\Command;

use CitOmni\Kernel\Command\BaseCommand;

/**
 * Hello World — smoke test for CLI dispatch.
 */
final class HelloCommand extends BaseCommand {

	public function run(array $argv = []): int {
		$name = $argv[2] ?? 'World';
		\fwrite(\STDOUT, "Hello, {$name}!" . \PHP_EOL);
		return 0;
	}
}
