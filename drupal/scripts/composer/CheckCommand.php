<?php

namespace DrupalProject\composer;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Check code quality/standards.
 */
class CheckCommand extends Command {

  /**
   * Check commands.
   */
  protected const CHECK_COMMANDS = [
    'phpstan analyse',
    'phpcs',
    'ec -d',
    'twig-cs-fixer --no-cache lint',
  ];

  /**
   * Fix commands.
   */
  protected const FIX_COMMANDS = [
    'phpcbf',
    'ec --fix -d',
    'twig-cs-fixer --no-cache lint --fix',
  ];

  /**
   * {@inheritdoc}
   */
  protected function configure(): void {
    $this->setDefinition([
      new InputOption('fix', null, InputOption::VALUE_NONE, 'Run automatic fixes.'),
      new InputArgument('targets', InputArgument::IS_ARRAY, 'Target directories to check.'),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function execute(InputInterface $input, OutputInterface $output): int {
    // By default check all custom themes and modules.
    $targets = ["web/themes/custom", "web/modules/custom"];
    // If given any arguments then check them instead of default targets set above.
    if (!empty($input->getArgument('targets'))) {
      $targets = $input->getArgument('targets');
    }

    // Determine which commands to run.
    $commands = $input->getOption('fix') ? self::FIX_COMMANDS : self::CHECK_COMMANDS;

    $anyCheckFailed = FALSE;
    // Check all given targets.
    foreach ($targets as $target) {
      // If given target is not a directory path exit.
      if (!is_dir($target)) {
        $output->writeln("<error>Passed targets must be valid directory paths. $target is not.</error>");
        return 1;
      }
      // Run commands for target.
      if (!$this->runCheckCommands($commands, $target, $output)) {
        $anyCheckFailed = TRUE;
      }
    }

    if ($anyCheckFailed) {
      $output->writeln("<error>Some checks have failed.</error>");
      return 1;
    }
    // All checks have passed.
    $output->writeln("<info>All checks have passed. Good job!</info>");
    return 0;
  }

  /**
   * Run given check commands.
   *
   * @return bool
   *   TRUE if all commands have run successfully, FALSE if at least one command
   *   had exit code not equal to 0.
   */
  private function runCheckCommands(array $commands, string $target, OutputInterface $output): bool {
    $allCommandsRunWithoutErrors = TRUE;
    foreach ($commands as $command) {
      $cmdWithArgs = "$command $target";
      $output->writeln("\n<info>Running `$cmdWithArgs`...</info>");
      $cmdOutput = [];
      exec($cmdWithArgs, $cmdOutput, $exitCode);
      $output->writeln($cmdOutput);
      if ($exitCode !== 0) {
        $allCommandsRunWithoutErrors = FALSE;
      }
    }
    return $allCommandsRunWithoutErrors;
  }

}
