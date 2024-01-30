<?php

namespace Oraculum\Observer;

use Oraculum\Observer\Abstracts\Observer;
use Oraculum\Support\Primitives\PrimitiveObject;

class Subject extends PrimitiveObject
{
    /**
     * All attachable observers.
     * 
     * @var array<Observer|Closure|string>
     */
    protected array $observers = [];

    /**
	 * Attach an Observer.
	 *
	 * @param Observer|Closure|string $observer The observer to attach.
     * 
	 * @return void
	 */
	final public function attach($observer): void
    {
        $this->observers[] = $observer;
    }

	/**
	 * Detach an observer.
	 *
	 * @param Observer|Closure|string $observer The observer to detach.
     * 
	 * @return void
	 */
	final public function detach($observer): void
    {
        if ($key = array_search($observer, $this->observers, true)) {
            unset($this->observers[$key]);
        }
    }

	/**
	 * Notify an observer.
     * 
	 * Notifies all attached observers.
     * 
     * @param string $action The action to notify.
	 * 
     * @return void
	 */
	final public function notify($action): void
    {
        foreach ($this->observers as $observer) {
            $observer instanceof Observer? $observer->observe($this, $action) : $observer($this, $action);
        }
    }
}