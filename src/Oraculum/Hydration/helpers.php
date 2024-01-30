<?php declare(strict_types=1);

if (!function_exists('hydrate')) {
    /**
     * Hydrates an object.
     * 
     * @template TObject of object
     * 
     * @param TObject $object The object to hydrate.
     * @param array   $data   The data to hydrate.
     * 
     * @return TObject The hydrated object.
     */
    function hydrate($object, $data)
    {
        $hydrator = new \Oraculum\Hydration\Hydrator($object);

        return $hydrator->hydrate($data);
    }
}