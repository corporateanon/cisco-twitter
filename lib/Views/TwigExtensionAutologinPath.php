<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace nanotwi\Views;
use nanotwi\Middleware\User;

/**
 * Description of TwigExtensionAutologinLink
 *
 * @author mk
 */
class TwigExtensionAutologinPath extends \Twig_Extension {

    private $container;

    public function __construct(\Interop\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return 'AutologinPath';
    }
    
    public function autologinPathFor($name, $data = [], $queryParams = []) {
        $router = $this->container->get('router');
        $token = $this->container->get('view')['token'];
        $dataWithToken  = array_merge($data, [ 'token' => $token ]);
        
        return $router->pathFor($name, $dataWithToken, $queryParams);
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('autologin_path_for', array($this, 'autologinPathFor'))
        ];
    }
    
}
