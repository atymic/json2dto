<?php
declare(strict_types=1);

namespace Atymic\Json2Dto\Commands;

use Atymic\Json2Dto\Generator\DtoGenerator;
use Atymic\Json2Dto\Helpers\NamespaceFolderResolver;
use Atymic\Json2Dto\Helpers\NameValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDto extends Command
{
    private const EXIT_SUCCESS = 0;
    private const EXIT_INVALID_JSON = 2;
    private const EXIT_INVALID_NAMESPACE = 3;

    protected function configure()
    {
        $this->setName('generate')
            ->setDescription('Generate DTO from a json string')
            ->addArgument('namespace', InputArgument::REQUIRED, 'Namespace to generate the class(es) in')
            ->addArgument('json', InputArgument::OPTIONAL, 'File containing the json string')
            ->addOption('classname', 'name', InputOption::VALUE_OPTIONAL, 'Class name of the new DTO', 'NewDto')
            ->addOption('nested', null, InputOption::VALUE_NONE, 'Generate nested DTOs')
            ->addOption('typed', null, InputOption::VALUE_NONE, 'Generate PHP >= 7.4 strict typing')
            ->addOption('flexible', null, InputOption::VALUE_NONE, 'Generate a flexible DTO')
            ->addOption('dry', null, InputOption::VALUE_NONE, 'Dry run, print generated files');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $decoded = null;
        $inputPath = $input->getArgument('json');

        if ($inputPath && file_exists($inputPath)) {
            $decoded = json_decode(file_get_contents($inputPath));
        }

        if (!$decoded) {
            stream_set_blocking(STDIN, false);
            $stdIn = file_get_contents('php://stdin');
            $decoded = json_decode($stdIn);
        }

        if (!$decoded) {
            $output->writeln('Failed to parse JSON file');
            return self::EXIT_INVALID_JSON;
        }

        if (!NameValidator::validateNamespace($input->getArgument('namespace'))) {
            $output->writeln('Invalid namespace string');
            return self::EXIT_INVALID_NAMESPACE;
        }

        $dryRun = $input->getOption('dry') !== false;

        $generator = new DtoGenerator(
            $input->getArgument('namespace'), // todo validate this
            $input->getOption('nested') !== false,
            $input->getOption('typed') !== false,
            $input->getOption('flexible') !== false
        );

        $generator->generate($decoded, $input->getOption('classname'));

        $namespaceResolver = new NamespaceFolderResolver($this->getComposerConfig());

        foreach ($generator->getFiles($namespaceResolver) as $path => $class) {
            if ($dryRun) {
                $output->writeln([$path, '', $class, '']);
                continue;
            }

            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            file_put_contents($path, $class);
            $output->writeln(sprintf('Created %s', $path));
        }

        return self::EXIT_SUCCESS;
    }

    private function getComposerConfig(): ?array
    {
        if (!file_exists('composer.json')) {
            return null;
        }

        return json_decode(file_get_contents('composer.json'), true);
    }
}
