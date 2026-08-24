<?php

namespace Pinoox\Component\PackageManager\Domain;

/**
 * Package runtime state definitions.
 */
enum PackageState: string
{
    case DISCOVERED = 'discovered';
    case AVAILABLE = 'available';
    case INSTALLING = 'installing';
    case INSTALLED = 'installed';
    case ACTIVE = 'active';
    case FAILED = 'failed';
    case REMOVED = 'removed';
}
