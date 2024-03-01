<?php

namespace Oraculum\Observer\Abstracts;

use Oraculum\Support\Primitives\PrimitiveObject;

abstract class Observer extends PrimitiveObject
{
	/**
	 * Observe an subject.
	 *
	 * @template TData
	 * 
	 * @param \Oraculum\Observer\Subject $subject The subject to observe.
	 * @param TData  					 $data  The data to observe.
     * 
	 * @return void
	 */
	public abstract function observe($subject, $data);
}