<?php

declare(strict_types=1);

use Pinoox\Component\PackageManager\Domain\Package;
use Pinoox\Component\PackageManager\Domain\PackageIdentifier;
use Pinoox\Component\PackageManager\Domain\PackageVersion;
use Pinoox\Component\PackageManager\Domain\Capability;

it('creates a valid package aggregate', function () {
    $package = new Package(
        new PackageIdentifier('com_shop'),
        new PackageVersion('1.0.0', 10000),
        [],
        [new Capability('shop')]
    );

    expect($package->name())->toBe('com_shop');
});

it('rejects empty package identifiers', function () {
    new PackageIdentifier('');
})->throws(InvalidArgumentException::class);

it('rejects invalid capabilities inside package', function () {
    new Package(
        new PackageIdentifier('com_invalid'),
        new PackageVersion('1.0.0', 10000),
        [],
        ['invalid-capability']
    );
})->throws(InvalidArgumentException::class);
