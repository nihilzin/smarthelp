<?php

/*
 * This file is part of Twig.
 *
 * (c) Urich Souza
 * (c) Nicolly Fagundes
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Node\Expression\Binary;

use Twig\Compiler;

class BitwiseOrBinary extends AbstractBinary
{
    public function operator(Compiler $compiler): Compiler
    {
        return $compiler->raw('|');
    }
}
