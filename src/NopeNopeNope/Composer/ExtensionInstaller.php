<?php

declare(strict_types=1);

namespace NopeNopeNope\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use InvalidArgumentException;
use Throwable;

final class ExtensionInstaller extends LibraryInstaller
{
    public const ERROR_MSG = 'Only one package can be installed into the configured webroot.';

    public const INSTALLER_TYPE = 'webroot';

    public function getInstallPath(PackageInterface $package): ?string
    {
        $prettyName = $package->getPrettyName();
        try {
            if ($this->composer->getPackage()) {
                $extra = $this->composer->getPackage()->getExtra();

                if (!empty($extra['webroot-dir']) && !empty($extra['webroot-package']) && $extra['webroot-package'] === $prettyName) {
                    return $extra['webroot-dir'];
                } else {
                    throw new InvalidArgumentException(self::ERROR_MSG);
                }
            }
        } catch (Throwable $e) {
            if($e->getMessage() === self::ERROR_MSG) {
                throw $e;
            }

            throw new InvalidArgumentException('The root package is not configured properly.');
        }
    }

    public function supports($packageType): bool
    {
        return $packageType === self::INSTALLER_TYPE;
    }
}
