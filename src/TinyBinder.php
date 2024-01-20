<?php

/**
 * Class TinyBinder
 *
 * A super lightweight templating class that merges content, and the results of custom functions, with an HTML file.
 */
class TinyBinder
{
    /** @var string Regular expression pattern for variable placeholders. */
    const VARIABLE_PATTERN = '/{{\s*\$(\S+)\s*}}/';

    /** @var string Regular expression pattern for function placeholders. */
    const FUNCTION_PATTERN = '/{{\s*\@(\S+)\s*}}/';

    /** @var string The HTML content of the template. */
    private $html = '';

    /** @var array An array to store assets for replacement in the template. */
    private $assets = [];

    /** @var bool Flag indicating whether to enable debugging mode. */
    private $debug = false;

    /**
     * Template constructor.
     *
     * @param string $input The path to a file or raw HTML input.
     */
    public function __construct($input)
    {
        // Load HTML content from file or use raw input.
        $this->html = file_exists($input) ? file_get_contents($input) : $input;
    }

    /**
     * Creates a new TinyBinder instance and initialises it with the provided path and values.
     *
     * @param string $path   The path to the template file.
     * @param array  $values An array of values to replace in the template.
     * @param bool   $debug  Flag indicating whether to enable debugging mode.
     *
     * @return string The processed HTML content.
     */
    public static function make($path, $values, $debug = false)
    {
        $engine = new self($path);
        $engine->debug($debug);
        $engine->addAssets($values);

        return $engine->getHtml();
    }

    /**
     * Enables or disables debugging mode.
     *
     * @param bool $debug Flag indicating whether to enable debugging mode.
     *
     * @return $this
     */
    public function debug($debug = true)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * Adds a single asset to the template.
     *
     * @param string $name  The name of the asset.
     * @param mixed  $value The value of the asset.
     *
     * @return $this
     */
    public function addAsset($name, $value)
    {
        $this->assets[$name] = $value;
        return $this;
    }

    /**
     * Adds multiple assets to the template.
     *
     * @param array $assets An associative array of assets.
     *
     * @return $this
     */
    public function addAssets($assets)
    {
        $this->assets = array_merge($this->assets, $assets);
        return $this;
    }

    /**
     * Replaces variable placeholders in the HTML content with corresponding values.
     *
     * @return $this
     */
    private function replaceVariables()
    {
        $assets = $this->assets;
        $debug = $this->debug;

        $html = preg_replace_callback(self::VARIABLE_PATTERN, function ($matches) use ($assets, $debug) {
            $variableName = $matches[1];

            if (!$debug && !isset($assets[$variableName])) {
                return '';
            }

            return isset($assets[$variableName]) ? $assets[$variableName] : $matches[0];
        }, $this->html);

        $this->html = $html;
        return $this;
    }

    /**
     * Replaces function placeholders in the HTML content with the result of corresponding functions.
     *
     * @return $this
     */
    private function replaceFunctions()
    {
        include __DIR__ . '/functions.php';
        $functions = get_defined_vars();
        $debug = $this->debug;

        $html = preg_replace_callback(self::FUNCTION_PATTERN, function ($matches) use ($functions, $debug) {
            $variableName = $matches[1];

            if (!isset($functions[$variableName])) {
                if (!$debug) {
                    return '';
                }
                return $matches[0];
            }

            $function = $functions[$variableName];
            return $function();
        }, $this->html);

        $this->html = $html;
        return $this;
    }

    /**
     * Gets the processed HTML content after variable and function replacement.
     *
     * @return string The processed HTML content.
     */
    public function getHtml()
    {
        $this->replaceVariables();
        $this->replaceFunctions();

        return $this->html;
    }
}
