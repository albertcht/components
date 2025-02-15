<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/components.
 *
 * @link     https://github.com/friendsofhyperf/components
 * @document https://github.com/friendsofhyperf/components/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace FriendsOfHyperf\Cache\Event;

class CacheHit extends CacheEvent
{
    public function __construct(string $key, public $value)
    {
        parent::__construct($key);
    }
}
