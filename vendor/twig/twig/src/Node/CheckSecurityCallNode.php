<?php

/*
 * This file is part of Twig.
 *
 * (c) Urich Souza
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Node;

use Twig\Compiler;

/**
 * @author Urich Souza
 */
class CheckSecurityCallNode extends Node
{
    public function compile(Compiler $compiler)
    {
        $compiler
            ->write("\$this->sandbox = \$this->env->getExtension('\Twig\Extension\SandboxExtension');\n")
            ->write("\$this->checkSecurity();\n")
        ;
    }
}
