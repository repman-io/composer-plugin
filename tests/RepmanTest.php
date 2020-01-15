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
use Composer\Package\RootPackageInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

final class RepmanTest extends TestCase
{
    /**
     * @var Repman
     */
    private $plugin;

    public function testMirrorPopulation(): void
    {
        // then
        $installPacakge = $this->prophesize(Package::class);
        $installPacakge->setDistMirrors(Argument::type('array'))
            ->shouldBeCalledTimes(1);

        $updatePacakge = $this->prophesize(Package::class);
        $updatePacakge->setDistMirrors(Argument::type('array'))
            ->shouldBeCalledTimes(1);

        // given
        $event = $this->prophesize(InstallerEvent::class);
        $event->getOperations()->willReturn([
            new InstallOperation($installPacakge->reveal()),
            new UpdateOperation($updatePacakge->reveal(), $updatePacakge->reveal()),
        ]);

        // when
        $this->plugin->populateMirrors($event->reveal());
    }

    public function testMirrorPopulationWithCustomUrl(): void
    {
        // then
        $installPacakge = $this->prophesize(Package::class);
        $installPacakge->setDistMirrors([
            [
                'url' => 'https://repman.custom/dists/%package%/%version%/%reference%.%type%',
                'preferred' => true,
            ],
        ])
            ->shouldBeCalledTimes(1);

        // given
        $package = $this->prophesize(RootPackageInterface::class);
        $package->getExtra()->willReturn([
            'repman' => ['url' => 'https://repman.custom'],
        ]);

        $composer = $this->prophesize(Composer::class);
        $composer->getPackage()->willReturn($package->reveal());

        $io = $this->prophesize(IOInterface::class);
        $this->plugin->activate($composer->reveal(), $io->reveal());

        $event = $this->prophesize(InstallerEvent::class);
        $event->getOperations()->willReturn([
            new InstallOperation($installPacakge->reveal()),
        ]);

        // when
        $this->plugin->populateMirrors($event->reveal());
    }

    protected function setUp(): void
    {
        $this->plugin = new Repman();

        $package = $this->prophesize(RootPackageInterface::class);
        $package->getExtra()->willReturn([]);

        $composer = $this->prophesize(Composer::class);
        $composer->getPackage()->willReturn($package->reveal());

        $io = $this->prophesize(IOInterface::class);
        $this->plugin->activate($composer->reveal(), $io->reveal());
    }
}
