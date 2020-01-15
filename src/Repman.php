<?php

declare(strict_types=1);

namespace Buddy\Repman\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\InstallerEvent;
use Composer\Installer\InstallerEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

final class Repman implements PluginInterface, EventSubscriberInterface
{
    public const VERSION = '0.1.0';
    public const DEFAULT_BASE_URL = 'https://127.0.0.1:8000';

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @return void
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->io = $io;
        $this->io->write(sprintf('Repman (%s) plugin activated', self::VERSION), true, IOInterface::VERBOSE);
        $this->baseUrl = rtrim($composer->getPackage()->getExtra()['repman']['url'] ?? self::DEFAULT_BASE_URL, '/');
    }

    /**
     * @return array<mixed>
     */
    public static function getSubscribedEvents()
    {
        return [
            InstallerEvents::POST_DEPENDENCIES_SOLVING => [['populateMirrors', '9'.PHP_INT_MAX]],
        ];
    }

    public function populateMirrors(InstallerEvent $installerEvent): void
    {
        $this->io->write(sprintf('Populate packages dist mirror url with with %s', $this->baseUrl), true, IOInterface::VERBOSE);

        foreach ($installerEvent->getOperations() as $operation) {
            /** @phpstan-var mixed $operation */
            if ('install' === $operation->getJobType()) {
                $package = $operation->getPackage();
            } elseif ('update' === $operation->getJobType()) {
                $package = $operation->getTargetPackage();
            } else {
                continue;
            }

            if (!method_exists($package, 'setDistMirrors')) {
                continue;
            }

            $package->setDistMirrors([
                [
                    'url' => $this->baseUrl.'/dists/%package%/%version%/%reference%.%type%',
                    'preferred' => true,
                ],
            ]);
        }
    }
}
