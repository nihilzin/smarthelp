<?php

/**
 * ---------------------------------------------------------------------
 *
 * Powered by Urich Souza 
 *
 * https://github.com/nihilzin
 *
 * @copyright 2023 Urich Souza and contributors.
 * 
 * ---------------------------------------------------------------------
 */

namespace Glpi\Application\View\Extension;

use Toolbox;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * @since 10.0.0
 */
class PhpExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('php_config', [$this, 'phpConfig']),
            new TwigFunction('call', [$this, 'call']),
            new TwigFunction('get_static', [$this, 'getStatic']),
            new TwigFunction('get_class', 'get_class'),
        ];
    }

    public function getTests(): array
    {
        return [
            new TwigTest('instanceof', [$this, 'isInstanceOf']),
            new TwigTest('usingtrait', [$this, 'isUsingTrait']),
            new TwigTest('array', 'is_array'),
            new TwigTest('object', 'is_object'),
        ];
    }

    /**
     * Get PHP configuration value.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function phpConfig(string $name)
    {
        return ini_get($name);
    }

    /**
     * Call function of static method.
     *
     * @param string $callable
     * @param array $parameters
     *
     * @return mixed
     */
    public function call(string $callable, array $parameters = [])
    {
        if (is_callable($callable)) {
            return call_user_func_array($callable, $parameters);
        }
        return null;
    }

    /**
     * Return static property value.
     *
     * @param mixed $class
     * @param string $property
     *
     * @return mixed
     */
    public function getStatic($class, string $property)
    {
        if ((is_object($class) || class_exists($class)) && property_exists($class, $property)) {
            return $class::$$property;
        }
        return null;
    }

    /**
     * Checks if a given value is an instance of given class name.
     *
     * @param mixed  $value
     * @param string $classname
     *
     * @return bool
     */
    public function isInstanceof($value, $classname): bool
    {
        return is_object($value) && $value instanceof $classname;
    }

    /**
     * Checks if a given value is an instance of class using given trait name.
     *
     * @param mixed  $value
     * @param string $trait
     *
     * @return bool
     */
    public function isUsingTrait($value, $trait): bool
    {
        return is_object($value) && Toolbox::hasTrait($value, $trait);
    }
}
