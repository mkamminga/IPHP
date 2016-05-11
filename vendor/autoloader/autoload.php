<?php
namespace Autoloader;
/**
 * @version  v1
 */
class ClassAutoloader {
	private $classMap = [];
    private $namespaces = [];
    /**
     * [__construct description]
     */
    public function __construct () {
        $this->register();
    }
    /**
     * [register description]
     * @return [type] [description]
     */
    public function register () {
        spl_autoload_register([$this, 'load'], true, true);
    }
    /**
     * [unregister description]
     * @return [type] [description]
     */
    public function unregister () {
        spl_autoload_unregister([$this, 'load']);
    }
	/**
     * @param array
     */
    public function addClassMap(array $classMap)
    {
        if ($this->classMap) {
            $this->classMap = array_merge($this->classMap, $classMap);
        } else {
            $this->classMap = $classMap;
        }
    }
    /**
     * [load description]
     * @return [type] [description]
     */
	public function loadNamespaces (array $paths) {
        if (empty($this->namespaces)) {
            $this->namespaces = $paths;
        } else {
            $this->namespaces = array_merge($this->namespaces, $paths);
        }
	}
    /**
     * [load description]
     * @param  string $file [description]
     * @return [type]       [description]
     */
    public function load($file = '') {
        if (array_key_exists($file, $this->classMap)) {
            include_once($this->classMap[$file]);

            return true;
        } else {
            $filePath = explode('\\',$file);
            $namespacePrefix = $filePath[0];
            if (array_key_exists($namespacePrefix, $this->namespaces)) {
                unset($filePath[0]);
                $suffexPath = '';
                if (!empty($filePath)) {
                    $suffexPath = implode($filePath, DIRECTORY_SEPARATOR);
                }

                foreach ($this->namespaces[$namespacePrefix] as $path) {
                    $filePath = $path .  DIRECTORY_SEPARATOR .  $suffexPath . '.php';

                    if (is_file($filePath)) {
                        include_once($filePath);
                        $this->classMap[$file] = $filePath;

                        return true;
                    }
                }
            } 
        }

        return false;
    }
}

$c = new ClassAutoloader();

$c->loadNamespaces(include ('paths.php'));

