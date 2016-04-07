<?php


namespace nanotwi\Controller;

use Interop\Container\ContainerInterface;

class Base {

    protected $ci;

    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
    }
    
    public function __get($name) {
        return $this->ci->get($name);
    }
}
