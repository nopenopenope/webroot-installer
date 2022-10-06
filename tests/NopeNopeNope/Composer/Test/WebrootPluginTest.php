<?php

declare(strict_types=1);

namespace NopeNopeNope\Composer\Test;

use Composer\Composer;
use Composer\Config;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use NopeNopeNope\Composer\ExtensionInstaller;
use NopeNopeNope\Composer\WebrootPlugin;
use PHPUnit\Framework\MockObject\MockObject;

final class WebrootPluginTest extends \PHPUnit\Framework\TestCase
{
    private WebrootPlugin $webrootPlugin;

    public function setUp(): void
    {
        $this->webrootPlugin = new WebrootPlugin();
    }

    public function test_it_will_activate_correctly(): void
    {
        $composerMock = $this->getComposerMock();
        $ioMock = $this->createMock(IOInterface::class);
        $installerMock = $this->createMock(InstallationManager::class);

        $composerMock->expects($this->once())
            ->method('getInstallationManager')
            ->willReturn($installerMock);

        $extension = new ExtensionInstaller($ioMock, $composerMock);
        $installerMock->expects($this->once())
            ->method('addInstaller')
            ->with($extension);

        $this->webrootPlugin->activate($composerMock, $ioMock);
    }

    public function test_it_will_deactivate_correctly(): void
    {
        $composerMock = $this->getComposerMock();
        $ioMock = $this->createMock(IOInterface::class);
        $installerMock = $this->createMock(InstallationManager::class);

        $composerMock->expects($this->exactly(2))
            ->method('getInstallationManager')
            ->willReturn($installerMock);

        $extension = new ExtensionInstaller($ioMock, $composerMock);
        $installerMock->expects($this->once())
            ->method('addInstaller')
            ->with($extension);

        $installerMock->expects($this->once())
            ->method('removeInstaller')
            ->with($extension);

        $this->webrootPlugin->activate($composerMock, $ioMock);
        $this->webrootPlugin->deactivate($composerMock, $ioMock);
    }

    private function getComposerMock(bool $withConfig = true): MockObject|Composer
    {
        $composerMock = $this->createMock(Composer::class);
        if($withConfig) {
            $configMock = $this->createMock(Config::class);
            $configMock->expects($this->any())
                ->method('get')
                ->with($this->anything())
                ->willReturn('something-something');

            $composerMock->expects($this->any())
                ->method('getConfig')
                ->willReturn($configMock);
        }

        return $composerMock;
    }
}
