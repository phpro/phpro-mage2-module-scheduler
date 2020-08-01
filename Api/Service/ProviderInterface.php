<?php
declare(strict_types=1);

namespace Phpro\Scheduler\Api\Service;

interface ProviderInterface
{
    public function provide(): array;
}
