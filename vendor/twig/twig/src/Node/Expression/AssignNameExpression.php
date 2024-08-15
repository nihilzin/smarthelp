<?php

/*
 * This file is part of Twig.
 *
 * (c) Urich Souza
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Node\Expression;

use Twig\Compiler;

class AssignNameExpression extends NameExpression
{
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->raw('$context[')
            ->string($this->getAttribute('name'))
            ->raw(']')
        ;
    }
}
