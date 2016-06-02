<?php
namespace IPHP\App\View;

use IPHP\App\ServiceManager;
use IPHP\View\Compiler\Compiler;
use IPHP\View\ViewResponse; 

class ViewStrategy {
    private $sm;
    private $compiler;
    
    public function __construct (ServiceManager $serviceManager) {
        $this->sm = $serviceManager;    
        
        if ($this->sm->hasConfig('app')){
            $paths = $this->sm->getConfig('app')->getValue('views');
            $this->compiler = new Compiler($paths['path'], $paths['compiled_path'], $paths['cache_map']);
        } else {
            throw new \Exception("Compiler viewpaths not set!");
        }
    }
    
    public function resolve (ViewResponse $vr) {
        $view = new View($vr, $this->compiler, $this->sm);
        $view->render();
    }
}