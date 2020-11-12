<?php

declare(strict_types=1);

namespace Buddy\Repman\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\Installer\InstallerEvents;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Plugin\PluginInterface;

final class Repman implements PluginInterface, EventSubscriberInterface
{
    public const VERSION = '0.1.2';
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
        return [
            InstallerEvents::POST_DEPENDENCIES_SOLVING => [['populateMirrors', '9'.PHP_INT_MAX]],
        ];
    }

    public function populateMirrors(InstallerEvent $installerEvent): void
    {
        $this->io->write(sprintf('Populate packages dist mirror url with %s', $this->baseUrl), true, IOInterface::VERBOSE);

        foreach ($installerEvent->getOperations() as $operation) {
            /** @phpstan-var mixed $operation */
            if ('install' === $operation->getJobType()) {
                $package = $operation->getPackage();
            } elseif ('update' === $operation->getJobType()) {
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
        // TODO: Implement uninstall() method.
    }
}
