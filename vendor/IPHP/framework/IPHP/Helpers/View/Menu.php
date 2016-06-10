<?php
namespace IPHP\Helpers\View;

use IPHP\Helpers\Url;
use IPHP\Http\Request;

class Menu {
    private $url;
    private $currentUrl;
    private $menuClass = [];
    private $menuAttrs = [];
    private $subMenuClass = [];
    private $subMenuAttr = [];


    public function __construct (Url $url, Request $request) {
        $this->url = $url;
        $this->currentUrl = $request->currentUrl();
    }

    public function setMenuClass (array $classes) {
        $this->menuClass = $classes;
    }

    public function setMenuAttrs (array $attrs) {
        $this->menuAttrs = $attrs;
    }

    public function setSubMenuClass (array $classes) {
        $this->subMenuClass = $classes;
    }

    public function setSubMenuAttrs (array $attrs) {
        $this->subMenuAttrs = $attrs;
    }

    public function displayMenu (array $items) {
        return $this->display($items, 0, $this->menuClass, $this->menuAttrs, $this->subMenuClass, $this->subMenuAttr);
    }

    public function display (array $items = [], int $tabCount, array $classes = [], array $attrs = [], array $childClass = [], array $childAttrs = []) {
        $tabs = str_repeat(chr(9), $tabCount);

        $output = $tabs.  '<ul';

        if  (!empty($classes)) {
             $output.= ' class="'. implode(' ', $classes) .'"';
        }

        if (!empty($attrs)) {
            $output.= ' ' . implode(' ', $attrs);
        }

        $output.= '>'. chr(13);

        foreach ($items as $menu):
            $params = isset($menu->params) ? $menu->params : [];
            $subMenu = isset($menu->subMenu) ? $menu->subMenu : [];
            $url = $this->url->route($menu->link, $params);
            $active = $url == $this->currentUrl;
            $output.= $tabs . chr(9) . '<li'. ($active ? ' class="active"' : '') .'>'. chr(13) . $tabs. chr(9) . chr(9) .'<a href="'. $url .'">'. $menu->name .'</a>' . chr(13);
            
            if (count($subMenu) > 0) {
                $output.=  $this->display($subMenu, ($tabCount + 2), $childClass, $childAttrs, $childClass, $childAttrs);
            }
            
            $output.=  $tabs. chr(9) .'</li>'.chr(13);
        endforeach;
        
        $output.= $tabs. '</ul>' . chr(13);
        
        return $output;
    }
}