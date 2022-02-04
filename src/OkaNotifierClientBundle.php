<?php

namespace Oka\Notifier\ClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 */
class OkaNotifierClientBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
