<?php

declare(strict_types=1);

namespace NopeNopeNope\Composer\Test;

use Composer\Downloader\DownloadManager;
use Composer\IO\IOInterface;
use InvalidArgumentException;
use NopeNopeNope\Composer\ExtensionInstaller;
use Composer\Package\RootPackage;
use Composer\Package\Package;
use Composer\Util\Filesystem;
use Composer\Composer;
use Composer\Config;

final class ExtensionInstallerTest extends TestCase
{
    private Composer $composer;

    private Filesystem $fs;

    private string $vendorDir;

    private string $binDir;

    private IOInterface $io;

    public function setUp(): void
    {
        $this->fs = new Filesystem();

        $this->vendorDir = realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR.'baton-test-vendor';
        $this->ensureDirectoryExistsAndClear($this->vendorDir);

        $this->binDir = realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR.'baton-test-bin';
        $this->ensureDirectoryExistsAndClear($this->binDir);

        $this->composer = new Composer();
        $config = new Config();
        $config->merge([
            'config' => [
                'vendor-dir' => $this->vendorDir,
                'bin-dir' => $this->binDir,
            ],
        ]);
        $this->composer->setConfig($config);

        $dm = $this->getMockBuilder(DownloadManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->composer->setDownloadManager($dm);
        $this->io = $this->createMock(IOInterface::class);
    }

    public function tearDown(): void
    {
        $this->fs->removeDirectory($this->vendorDir);
        $this->fs->removeDirectory($this->binDir);
    }

    /**
     * @dataProvider dataForTestSupport
     */
    public function testSupports($type, $expected): void
    {
        $installer = new ExtensionInstaller($this->io, $this->composer);
        $this->assertSame($expected, $installer->supports($type), sprintf('Failed to show support for %s', $type));
    }

    public function dataForTestSupport()
    {
        return [
            [ExtensionInstaller::INSTALLER_TYPE, true],
        ];
    }

    public function testWebrootInstallPath()
    {
        $installer = new ExtensionInstaller($this->io, $this->composer);
        $package = new Package('nopenopenope/webroot-package', '1.0.0', '1.0.0');
        $package->setType('webroot');

        $consumerPackage = new RootPackage('foo/bar', '1.0.0', '1.0.0');
        $this->composer->setPackage($consumerPackage);
        $consumerPackage->setExtra(
            array(
                'webroot-dir' => 'content',
                'webroot-package' => 'nopenopenope/webroot-package',
            )
        );

        $result = $installer->getInstallPath($package);
        $this->assertEquals('content', $result);
    }

    public function testGetWebrootConfigurationException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The root package is not configured properly.');
        $installer = new ExtensionInstaller($this->io, $this->composer);
        $package = new Package('nopenopenope/webroot-package', '1.0.0', '1.0.0');
        $package->setType('webroot');
        $installer->getInstallPath($package);
    }

    public function testGetMultipleWebrootPackagesException(): void
    {
        $installer = new ExtensionInstaller($this->io, $this->composer);
        $package1 = new Package('nopenopenope/webroot-package', '1.0.0', '1.0.0');
        $package2 = new Package('nopenopenope/another-webroot-package', '1.0.0', '1.0.0');

        $rootPackage = new RootPackage('foo/bar', '1.0.0', '1.0.0');
        $this->composer->setPackage($rootPackage);
        $rootPackage->setExtra([
            'webroot-dir' => 'test-value',
            'webroot-package' => 'nopenopenope/webroot-package',
        ]);

        $rootPackage->setRequires([
            'nopenopenope/webroot-package' => $package1,
            'nopenopenope/another-webroot-package' => $package2
        ]);

        $resolve = $installer->getInstallPath($package1);
        $this->assertSame('test-value', $resolve);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only one package can be installed into the configured webroot.');
        $installer->getInstallPath($package2);
    }

    public function testItThrowsAnExceptionWhenNothingIsSetup(): void
    {
        $installer = new ExtensionInstaller($this->io, $this->composer);
        $package1 = new Package('nopenopenope/webroot-package', '1.0.0', '1.0.0');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The root package is not configured properly.');
        $installer->getInstallPath($package1);
    }
}
