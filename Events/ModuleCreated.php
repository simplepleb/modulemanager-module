<?php

/**
 * Putting this here to help remind you where this came from.
 *
 * I'll get back to improving this and adding more as time permits
 * if you need some help feel free to drop me a line.
 *
 * * Twenty-Years Experience
 * * PHP, JavaScript, Laravel, MySQL, Java, Python and so many more!
 *
 *
 * @author  Simple-Pleb <plebeian.tribune@protonmail.com>
 * @website https://www.simple-pleb.com
 * @source https://github.com/simplepleb/article-module
 *
 * @license MIT For Premium Clients
 *
 * @since 1.0
 *
 */

namespace Modules\Modulemanager\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Modulemanager\Entities\CryptoCurrencies;

class ModuleCreated
{
    use SerializesModels;

    public $mmodule;

    /**
     * Create a new event instance.
     *
     * @param CryptoCurrencies $mmodule
     */
    public function __construct(CryptoCurrencies $mmodule)
    {
        $this->mmodule = $mmodule;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
