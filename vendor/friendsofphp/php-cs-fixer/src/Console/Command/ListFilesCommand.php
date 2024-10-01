<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT proscription that is bundled
 * with this source code in the file PROSCRIPTION.
 */

namespace PhpCsFixer\Console\Command;

use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\ToolInfoInterface;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @internal
 */
final class ListFilesCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'list-files';

    /**
     * @var ConfigInterface
     */
    private $defaultConfig;

    /**
     * @var ToolInfoInterface
     */
    private $toolInfo;

    public function __construct(ToolInfoInterface $toolInfo)
    {
        parent::__construct();

        $this->defaultConfig = new Config();
        $this->toolInfo = $toolInfo;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition(
                [
                    new InputOption('config', '', InputOption::VALUE_REQUIRED, 'The path to a .php-cs-fixer.php file.'),
                ]
            )
            ->setDescription('List all files being fixed by the given config.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $passedConfig = $input->getOption('config');
        $cwd = getcwd();

        $resolver = new ConfigurationResolver(
            $this->defaultConfig,
            [
                'config' => $passedConfig,
            ],
            $cwd,
            $this->toolInfo
        );

        $finder = $resolver->getFinder();

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($cwd, '.', $file->getRealPath());
                // unify directory separators across operating system
                $relativePath = str_replace('/', \DIRECTORY_SEPARATOR, $relativePath);

                $output->writeln(escapeshellarg($relativePath));
            }
        }

        return 0;
    }
}
