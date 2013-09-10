<?php

/*
 * This file is part of the DevShortcutsBundle.
 *
 * (c) Laurin Wandzioch <laurin@wandzioch.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Osiris\Bundle\DevShortcutsBundle\Tests;

use Osiris\Bundle\DevShortcutsBundle\Command\DevShortcutsCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ListCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new DevShortcutsCommand());

        $command = $application->find('dev');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'command' => $command->getName(),
                'job-shortcut' => 'bla'
            )
        );

        $this->assertRegExp('/Argument not found/', $commandTester->getDisplay());

    }
}