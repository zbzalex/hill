<?php

namespace Hill;

require_once __DIR__ . "/IInitializationClass.php";

//
// Загрузчик классов.
// Концепт собственной реализации заключается в частичной модификации поведения
// загружаемых классов.
// Если класс наследует интерфейс IInitializationClass, то после загрузки файла,
// в котором хранится класс, будет вызван статический метод initialization.
// Это подобно выполнению блока static в java.
//
class ClassLoader
{
    /**
     * @var array
     */
    private $prefixes;

    /**
     * @var string[]
     */
    private $fallbackDirs;
    
    /**
     * 
     */
    public function __construct()
    {
        $this->fallbackDirs = [];
    }

    /**
     * Указывает путь, который будет привязан к имени пространства.
     * Важно! Указывать путь нужно с слешэм в конце.
     */
    public function addPrefix($prefix, $path)
    {
        $this->prefixes[$prefix] = $path;
    }

    /**
     * Указывает запасной путь загрузки классов.
     * Важно! Указывать путь нужно с слешэм в конце.
     */
    public function addFallbackDir($path)
    {
        $this->fallbackDirs[] = $path;
    }

    /**
     * 
     */
    public function register()
    {
        spl_autoload_register([$this, 'autoload']);
    }

    /**
     * @param string $class
     */
    public function autoload($class)
    {
        $class = ltrim($class, "\\");

        $namespace = ($pos = strrpos($class, "\\")) !== false ? substr($class, 0, $pos + 1) : null;
        $className = ($pos = strrpos($class, "\\")) !== false ? substr($class, $pos + 1) : $class;

        $classFileLoaded = false;
        if (isset($this->prefixes[$namespace])) {
            $filename = $this->prefixes[$namespace]
                . $className
                . ".php";
            $classFileLoaded = $this->loadFile($filename);
        } else {
            foreach ($this->fallbackDirs as $fallbackDir) {
                $filename = $fallbackDir
                    . $this->normalizeNamespaceToPath($namespace)
                    . $className
                    . ".php";

                if ($classFileLoaded = $this->loadFile($filename))
                    break;
            }
        }

        // java like static block
        // initialization class
        if ($classFileLoaded) {
            if (class_exists($class)) {
                try {
                    $reflectionClass = new \ReflectionClass($class);
                    if ($reflectionClass->implementsInterface(IInitializationClass::class)) {
                        $reflectionMethod = $reflectionClass->getMethod('initialization');
                        $reflectionMethod->invoke(null);
                    }
                } catch (\ReflectionException $e) {
                    // ignore
                }
            }
        }
    }

    /**
     * @param string $filename
     * 
     * @return bool file load result
     */
    private function loadFile($filename)
    {
        if (file_exists($filename)) {
            require_once $filename;

            return true;
        }

        return false;
    }

    /**
     * @param string $namespace
     * 
     * @return string formatted namespace to use in path
     */
    private function normalizeNamespaceToPath($namespace)
    {
        return $namespace !== null
            ? str_replace("\\", "/", $namespace) . "/"
            : null;
    }
}
