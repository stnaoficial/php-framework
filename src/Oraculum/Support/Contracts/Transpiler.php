<?php

namespace Oraculum\Support\Contracts;

interface Transpiler
{
    /**
     * Transpiles the source code into the target language.
     * 
     * @template TSource
     * @template TTarget
     * 
     * @param TSource $source The source code to transpile.
     * 
     * @return TTarget The transpiled code.
     */
    public function transpile($source);
}