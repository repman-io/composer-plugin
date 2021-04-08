<?php

declare(strict_types=1);

namespace Buddy\Repman\Composer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\Installer\InstallerEvents;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Plugin\PluginInterface;

final class Repman implements PluginInterface, EventSubscriberInterface
{
    /** @var string */
    public const VERSION = '1.1.1';

    /** @var string */
    public const DEFAULT_BASE_URL = 'https://repo.repman.io';

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var string
     */
    private $baseUrl;

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->io = $io;
        $this->io->write(sprintf('Repman plugin (%s) activated', self::VERSION), true, IOInterface::VERBOSE);
        $this->baseUrl = rtrim($composer->getPackage()->getExtra()['repman']['url'] ?? self::DEFAULT_BASE_URL, '/');
    }

    /**
     * @return array<mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [InstallerEvents::PRE_OPERATIONS_EXEC => ['populateMirrors', PHP_INT_MAX]];
    }

    public function populateMirrors(InstallerEvent $installerEvent): void
    {
        $this->io->write(sprintf('Populate packages dist mirror url with %s', $this->baseUrl), true, IOInterface::VERBOSE);

        $transaction = $installerEvent->getTransaction();
        if ($transaction === null) {
            return;
        }

        foreach ($transaction->getOperations() as $operation) {
            if ('install' === $operation->getOperationType()) {
                /** @var InstallOperation $operation */
                $package = $operation->getPackage();
            } elseif ('update' === $operation->getOperationType()) {
                /** @var UpdateOperation $operation */
                $package = $operation->getTargetPackage();
            } else {
                continue;
            }

            /** @var PackageInterface $package */
            if (!method_exists($package, 'setDistMirrors')) {
                continue;
            }

            if (strpos((string) $package->getNotificationUrl(), 'packagist.org') === false) {
                continue;
            }

            $package->setDistMirrors([
                [
                    'url' => $this->baseUrl.'/dists/%package%/%version%/%reference%.%type%',
                    'preferred' => true,
                ],
            ]);

            if (method_exists($package, 'setNotificationUrl')) {
                $package->setNotificationUrl($this->baseUrl.'/downloads');
            }
        }
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        $this->io->write(sprintf('Repman plugin (%s) deactivated', static::VERSION), true, IOInterface::VERBOSE);
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // nothing to do here ;)
    }
}
