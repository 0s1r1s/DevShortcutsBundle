<?php
namespace Osiris\Bundle\DevShortcutsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class DevShortcutsCommand extends ContainerAwareCommand
{
    /**
     * Configuration for DevShortcutsCommand
     */
    protected function configure()
    {
        $this
            ->setName('dev')
            ->setDescription('Development Shortcuts')
            ->addArgument(
                'job-shortcut',
                InputArgument::REQUIRED,
                'Shortcut for the job to run'
            )
            ->setHelp('The <info>dev</info> command executes some existing command combinations or single commands with shortcuts.

The following <info>job-shortcuts</info> are available:
<info>cc</info>         Clear cache
           <error>(cache:clear)</error>
<info>ai</info>         Install assets
           <error>(assets:install)</error>
<info>ad</info>         Dump assets
           <error>(assetic:dump)</error>
<info>aw</info>         Watch assets
           <error>(assetic:dump --watch)</error>
<info>a</info>          Install & dump assets (ai + ad combination)
<info>dd</info>         Drop database
           <error>(doctrine:database:drop --force)</error>
<info>dc</info>         Create database
           <error>(doctrine:database:create)</error>
<info>sd</info>         Schema drop
           <error>(doctrine:schema:drop --force)</error>
<info>sc</info>         Schema create
           <error>(doctrine:schmea:create)</error>
<info>dform</info>      Load ORM DataFixtures.
           <error>(doctrine:fixtures:load --fixtures=./src/BundlePath/DataFixtures/ORM)</error>
<info>dfenv</info>      Load environment related DataFixtures.
           <error>(doctrine:fixtures:load --append --fixtures=./src/BundleName/DataFixtures/dev)</error>
<info>d</info>          Reloads the database: Drop database scheme, create scheme, load orm + env data fixtures (sd, sc, dform, dfenv shortcut combination)

I suggest to create an alias for <info>php app/console</info> like <info>sf</info>
One command would look like this:
<info>sf dev d</info>
Look at the documentation to learn more about this.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jobShortcut = $input->getArgument('job-shortcut');
        $this->runJobByShortcut($jobShortcut, $output, $input);
    }

    protected function runJobByShortcut($shortCut, OutputInterface $output, InputInterface $input = null) {
        switch ($shortCut) {
            // reset the database (drop + create schema and load fixtures)
            case 'd':
                $jobShortcuts = array('sd', 'sc', 'dform', 'dfenv');
                $this->runJobsByShortcut($jobShortcuts, $output, $input);
                break;
            // install & dump assets
            case 'a':
                $jobShortcuts = array('ai', 'ad');
                $this->runJobsByShortcut($jobShortcuts, $output, $input);
                break;
            // drop the database
            case 'dd':
                $options = array('command' => 'doctrine:database:drop', '--force' => true);
                break;
            // create the database
            case 'dc':
                $options = array('command' => 'doctrine:database:create');
                break;
            // drop database schema
            case 'sd':
                $options = array('command' => 'doctrine:schema:drop', '--force' => true);
                break;
            // create database schema
            case 'sc':
                $options = array('command' => 'doctrine:schema:create');
                break;
            // load ORM data fixtures
            case 'dform':
                $fixturesPath = $this->getContainer()->getParameter('osiris_dev_shortcuts.path_to_fixtures');
                $options = array('command' => 'doctrine:fixtures:load', '--fixtures' => $fixturesPath . '/ORM');
                break;
            // load environment related data fixtures
            case 'dfenv':
                $fixturesPath = $this->getContainer()->getParameter('path_to_fixtures');
                $options = array('command' => 'doctrine:fixtures:load', '--fixtures' => $fixturesPath . '/' . $input->getOption('env'), '--append' => true);
                break;
            // install the assets
            case 'ai':
                $options = array('command' => 'assets:install');
                break;
            // dump the assets
            case 'ad':
                $options = array('command' => 'assetic:dump');
                break;
            // watch assets
            case 'aw':
                $options = array('command' => 'assetic:dump', '--watch' => true);
                break;
            // clear the cache
            case 'cc':
                $options = array('command' => 'cache:clear');
                break;
            default:
                $output->writeln('<info>Argument not found. Type <error>php app/console dev --help</error> to get a list of all commands.</info>');
        }
        // check for options variable and run the command
        if (isset($options)) {
            $input = new ArrayInput($options);
            $this->getApplication()->find($options['command'])->run($input, $output);
        }
    }

    /**
     * Loops an array of shortcuts and executes each job
     *
     * @param array $shortCuts
     * @param OutputInterface $output
     * @param InputInterface $input
     */
    protected function runJobsByShortcut(array $shortCuts, OutputInterface $output, InputInterface $input = null) {
        foreach($shortCuts as $shortCut) {
            $this->runJobByShortcut($shortCut, $output, $input);
        }
    }
}