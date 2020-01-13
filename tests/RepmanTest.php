<?php

declare(strict_types=1);

namespace Buddy\Repman\Composer\Tests;

use Buddy\Repman\Composer\Repman;
use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\Installer\InstallerEvent;
use Composer\IO\IOInterface;
use Composer\Package\Package;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

final class RepmanTest extends TestCase
{
    private Repman $plugin;

    public function testMirrorPopulation(): void
    {
        $installPacakge = $this->prophesize(Package::class);
        $installPacakge->setDistMirrors(Argument::type('array'))
            ->shouldBeCalledTimes(1);

        $updatePacakge = $this->prophesize(Package::class);
        $updatePacakge->setDistMirrors(Argument::type('array'))
            ->shouldBeCalledTimes(1);

        $event = $this->prophesize(InstallerEvent::class);
        $event->getOperations()->willReturn([
            new InstallOperation($installPacakge->reveal()),
            new UpdateOperation($updatePacakge->reveal(), $updatePacakge->reveal()),
        ]);

        $this->plugin->populateMirrors($event->reveal());
    }

    protected function setUp(): void
    {
        $this->plugin = new Repman();
        $composer = $this->prophesize(Composer::class);
        $io = $this->prophesize(IOInterface::class);
        $this->plugin->activate($composer->reveal(), $io->reveal());
    }
}
