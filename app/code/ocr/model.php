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

    const DIR_CACHE = DIR_TMP . 'ocr' . DS;

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
            $cachefile = self::DIR_CACHE . preg_replace('/\.[^.]+$/', '', basename($this->image)) . ".ocr-cache";
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
            if (!is_dir(self::DIR_CACHE)) {
                mkdir(self::DIR_CACHE);
            }
            $cache = implode(EOL, $this->text);
            $cachefile = self::DIR_CACHE . preg_replace('/\.[^.]+$/', '', basename($this->image)) . ".ocr-cache";
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
     * Get Pattern
     */
    function getPattern($pattern, $return_index=0)
    {
        $found = [];
        $text = $this->getText();
        foreach ($text as $line)
        {
            if (preg_match_all($pattern, $line, $matches))
            {
                $found = array_merge($found, $matches[$return_index]);
            }
        }

        if (empty($found))
            return false;

        return $found;
    }

    /**
     * Get dollar amounts
     */
    function getDollars()
    {
        return $this->getPattern('/\d+\.\d{2}/');
    }

    /**
     * Get date(s)
     */
    function getDates()
    {
        return $this->getPattern('/\d{1,2}\/\d{1,2}\/\d{2,4}/');
    }
}
