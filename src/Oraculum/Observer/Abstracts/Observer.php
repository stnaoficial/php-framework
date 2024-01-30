<?php

namespace Oraculum\Observer\Abstracts;

use Oraculum\Support\Primitives\PrimitiveObject;

abstract class Observer extends PrimitiveObject
{
	/**
	 * Observe an subject.
	 *
	 * @param \Oraculum\Observer\Subject $subject The subject to observe.
	 * @param string  					 $action  The action to observe.
     * 
	 * @return void
	 */
	public abstract function observe($subject, $action);
}