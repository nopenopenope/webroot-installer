<?php

declare(strict_types=1);

namespace NopeNopeNope\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

final class WebrootInstaller implements PluginInterface
{
    private const EXTENSIONS = [
        ExtensionInstaller::class
    ];

    private array $installers = [];

    public function activate(Composer $composer, IOInterface $io)
    {
        foreach(self::EXTENSIONS as $extension) {
            $installer = new $extension($io, $composer);
            $composer->getInstallationManager()->addInstaller($installer);
            $this->installers[] = $installer;
        }
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        foreach ($this->installers as $installer) {
            $composer->getInstallationManager()->removeInstaller($installer);
        }
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        return;
    }
}
