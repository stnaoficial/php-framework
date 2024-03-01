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
	 * Notifies all attached observers.
     * 
     * @template TData
     * 
     * @param TData $data The data to notify.
	 * 
     * @return void
	 */
	final public function notify($data): void
    {
        foreach ($this->observers as $observer) {
            $observer instanceof Observer? $observer->observe($this, $data) : $observer($this, $data);
        }
    }
}