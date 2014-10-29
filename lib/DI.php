<?php
/**
 * Created by Ivo Stefanov
 * Date: 10/29/14
 * Time: 3:33 AM
*/

require_once 'Request.php';

class DI {
    protected $shared = [];
    protected $definitions = [];

    public function __construct() {}

    /**
     * Registers a definition by a given name, the resource could be shared
     * @param $name
     * @param $definition
     * @param bool $shared
     * @throws Exception
     */
    public function set($name, $definition, $shared = false) {
        if(is_callable($definition) || (is_string($definition) && class_exists($definition)) || is_object($definition)){
            if(isset($definitions[$name])) {
                throw new Exception("Definition '$name' is already set. Use DI::remove('$name') to remove it.");
            }

            $this->definitions[$name] = $definition;

            if($shared) {
                $this->shared[$name] = true;
            }
        } else {
            throw new Exception("Provided definition is not a closure or a valid existing class.");
        }
    }

    /**
     * Gets the definition as a raw value without rendering it
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function getRaw($name) {
        if(!isset($this->definitions[$name])) {
            throw new Exception("Definition '$name' is not set. Use DI::set('$name', \$definition) to set it.");
        }

        return $this->definitions[$name];
    }

    /**
     * Renders the definition and returns it
     * @param $name
     * @return mixed|string
     * @throws Exception
     */
    public function get($name) {
        $definition = $this->getRaw($name);
        if(is_callable($definition)) return $definition();
        if(is_string($definition) && class_exists($definition)) return new $definition();
        if(is_object($definition)) return clone $definition;
        throw new Exception("Provided definition is not a closure or a valid existing class.");
    }

    /**
     * Gets the shared object, if not rendered it renders it first
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function getShared($name) {
        if(!isset($this->definitions[$name])) {
            throw new Exception("Shared definition '$name' is not set. Use DI::set('$name', \$definition, true) to set it.");
        }

        $definition = $this->definitions[$name];
        if(is_callable($definition)) $this->shared[$name] = $definition();
        else if(is_string($definition) && class_exists($definition)) $this->shared[$name] = new $definition();
        else if(is_object($definition)) $this->shared[$name] = $definition;

        return $this->shared[$name];
    }

    /**
     * Removes a definition by its name
     * @param $name
     * @throws Exception
     */
    public function remove($name) {
        if(!isset($this->definitions[$name])) {
            throw new Exception("Definition '$name' is not set. Use DI::set('$name', \$definition, [\$shared]) to set it.");
        }

        unset($this->definitions[$name]);
        unset($this->shared[$name]);
    }
}
