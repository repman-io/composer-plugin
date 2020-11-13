<?php

declare(strict_types=1);

namespace Buddy\Repman\Composer\Tests;

use Buddy\Repman\Composer\Repman;
use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\DependencyResolver\Transaction;
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
        $installPackage = $this->prophesize(Package::class);
        $installPackage->getNotificationUrl()->willReturn('https://packagist.org/downloads/');
        $installPackage->setDistMirrors(Argument::type('array'))
            ->shouldBeCalledTimes(1);
        $installPackage->setNotificationUrl('https://repo.repman.io/downloads')
            ->shouldBeCalledTimes(1);

        $updatePackage = $this->prophesize(Package::class);
        $updatePackage->getNotificationUrl()->willReturn('https://packagist.org/downloads/');
        $updatePackage->setDistMirrors(Argument::type('array'))
            ->shouldBeCalledTimes(1);
        $updatePackage->setNotificationUrl('https://repo.repman.io/downloads')
            ->shouldBeCalledTimes(1);

        // given
        $transaction = $this->prophesize(Transaction::class);
        $transaction->getOperations()->willReturn([
            new InstallOperation($installPackage->reveal()),
            new UpdateOperation($updatePackage->reveal(), $updatePackage->reveal()),
        ]);

        $event = $this->prophesize(InstallerEvent::class);
        $event->getTransaction()->willReturn($transaction);

        // when
        $this->plugin->populateMirrors($event->reveal());
    }

    public function testSkipPackageNotFromPackagist(): void
    {
        //then
        $installPackage = $this->prophesize(Package::class);
        $installPackage->getNotificationUrl()->willReturn('https://buddy.repo.repman.wip/downloads');
        $installPackage->setDistMirrors(Argument::type('array'))
            ->shouldNotBeCalled();

        // given
        $transaction = $this->prophesize(Transaction::class);
        $transaction->getOperations()->willReturn([
            new InstallOperation($installPackage->reveal()),
        ]);

        $event = $this->prophesize(InstallerEvent::class);
        $event->getTransaction()->willReturn($transaction);

        // when
        $this->plugin->populateMirrors($event->reveal());
    }

    public function testMirrorPopulationWithCustomUrl(): void
    {
        // then
        $installPackage = $this->prophesize(Package::class);
        $installPackage->getNotificationUrl()->willReturn('https://packagist.org/downloads/');
        $installPackage->setDistMirrors([
            [
                'url' => 'https://repman.custom/dists/%package%/%version%/%reference%.%type%',
                'preferred' => true,
            ],
        ])
            ->shouldBeCalledTimes(1);
        $installPackage->setNotificationUrl('https://repman.custom/downloads')
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

        $transaction = $this->prophesize(Transaction::class);
        $transaction->getOperations()->willReturn([
            new InstallOperation($installPackage->reveal()),
        ]);

        $event = $this->prophesize(InstallerEvent::class);
        $event->getTransaction()->willReturn($transaction);

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
