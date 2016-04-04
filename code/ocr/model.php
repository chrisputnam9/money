<?php
namespace MCPI;

/**
 * OCR Model
 * - Recognize text from images
 */
// TODO
// - Use account names, numbers as training data
// - date patterns, others?
class OCR_Model extends Core_Model_Abstract
{

    protected $cache = true;
    protected $image;
    protected $text;

    const DIR_CACHE = DIR_TMP . 'tesseract' . DS;

    /**
     * Construct with image
     */
    function __construct($image=false, $cache=true)
    {
        $this->cache = $cache;

        if ($image)
            $this->setImage($image);
    }

    /**
     * Set image
     */
    function setImage($image)
    {
        if (!is_file($image))
            die('Invalid image file: ' . $image);

        $this->image = $image;

        $this->loadCache();
    }

    /**
     * Load Cache if enabled and exists
     */
    function loadCache()
    {
        if ($this->cache and $this->image)
        {
            $cachefile = self::DIR_CACHE . basename($this->image) . ".ocr-cache";
            if (is_file($cachefile))
            {
                try {
                    $this->text = explode(EOL, file_get_contents($cachefile));
                } catch (Exception $e) {
                    // We'll just ignore errors here
                }
            }
        }
    }

    /**
     * Save Cache if enabled
     */
    function saveCache()
    {
        if ($this->cache and $this->image and $this->text)
        {
            $cache = implode(EOL, $this->text);
            $cachefile = self::DIR_CACHE . basename($this->image) . ".ocr-cache";
            file_put_contents($cachefile, $cache);
        }
    }

    /**
     * Get text from image
     */
    function getText()
    {
        if (is_null($this->text))
        {
            $image = $this->image;

            $command = 'tesseract "' . $image . '" stdout 2>&1';

            exec($command, $output, $return);

            if ($return != 0)
            {
                echo "OCR Error: ";
                die("<pre>".print_r($output,true)."</pre>");
            }

            $this->text = $output;
            $this->saveCache();
        }
        return $this->text;
    }

    /**
     * Get dollar amounts
     */
    function getDollars()
    {
        $text = $this->getText();
        $dollars = [];
        foreach ($text as $line)
        {
            if (preg_match_all('/\$\s*(\d*\.\d*)/', $line, $matches))
            {
                $dollars = array_merge($dollars, $matches[1]);
            }
        }
        return $dollars;
    }
}
