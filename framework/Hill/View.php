<?php

namespace Hill;

/**
 * View class
 */
class View implements \ArrayAccess
{
    public $path;
    public $extension = '.php';
    private $vars = [];
    private $template;
    private $helpers = [];

    /**
     * Contructor
     * 
     * @param string $path Base path
     */
    public function __construct($path = '.')
    {
        $this->path = $path;
    }

    public function get($key)
    {
        return isset($this->vars[$key])
            ? $this->vars[$key]
            : null;
    }

    public function set($key, $value = null)
    {
        if (\is_array($key) || \is_object($key)) {
            foreach ($key as $k => $v) {
                $this->vars[$k] = $v;
            }
        } else {
            $this->vars[$key] = $value;
        }
    }

    public function has($key)
    {
        return isset($this->vars[$key]);
    }

    public function clear($key = null)
    {
        if (null === $key) {
            $this->vars = [];
        } else {
            unset($this->vars[$key]);
        }
    }

    public function register(IViewHelper $helper)
    {
        $this->helpers[$helper->getName()] = $helper;
    }

    private function render($file, array $data = [])
    {
        $this->template = $this->getTemplate($file);

        if (!file_exists($this->template)) {
            throw new \Exception("Template file not found: {$this->template}.");
        }

        unset($file);

        if (\is_array($data)) {
            $this->vars = array_merge($this->vars, $data);

            if (isset($this->vars['view'])) {
                unset($this->vars['view']);
            }

            unset($data);
        }

        /** @var array $view */
        $view = $this;

        extract($this->vars);

        include $this->template;
    }

    public function fetch($file, array $data = [])
    {
        ob_start();

        $this->render($file, $data);

        return ob_get_clean();
    }

    private function exists($file)
    {
        return file_exists($this->getTemplate($file));
    }

    private function getTemplate($file)
    {
        $ext = $this->extension;

        if (!empty($ext) && (substr($file, -1 * \strlen($ext)) != $ext)) {
            $file .= $ext;
        }

        if (('/' == substr($file, 0, 1))) {
            return $file;
        }

        return $this->path . '/' . $file;
    }

    public function e($str)
    {
        return htmlentities($str, ENT_QUOTES);
    }

    public function offsetExists($key): bool
    {
        return isset($this->helpers[$key]);
    }

    public function offsetGet($key)
    {
        return isset($this->helpers[$key])
            ? $this->helpers[$key]
            : null;
    }

    public function offsetSet($key, $val): void
    {
        // 
    }

    public function offsetUnset($key): void
    {
        // 
    }
}
