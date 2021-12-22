<?php

namespace App\Listeners;

use App\Events\DetectionNewF;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DetectionNewFListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\DetectionNewF  $event
     * @return void
     */
    public function handle(DetectionNewF $event)
    {
        //
    }
}
