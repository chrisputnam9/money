<?php
namespace MCPI;

use Exception;

/**
 * OCR Model
 * - Recognize text from images
 */
class OCR_Model extends Core_Model_Abstract
{
    const DIR_CACHE = DIR_TMP . 'ocr' . DS;

    public static $months_short = array(
        'jan', 'feb', 'mar', 'apr', 'may', 'jun',
        'jul', 'aug', 'sep', 'oct', 'nov', 'dec',
    );

    public static $months_long = array(
        'january', 'febuary', 'march', 'april', 'may', 'june',
        'july', 'august', 'september', 'october', 'november', 'december',
    );

    protected $cache = true;
    protected $cache_file = null;

    protected $image = null;
    protected $text_file = null;

    protected $text = null;

    /**
     * Construct with image
     */
    function __construct($image=false, $text_file=false, $cache=true)
    {
        $this->cache = $cache;

        if ($image)
            $this->setImage($image);

        if ($text_file)
            $this->setTextFile($text_file);
    }

    /**
     * Get cache file path
     */
    protected function getCacheFile()
    {
        if (is_null($this->cache_file))
        {
            $this->cache_file = self::DIR_CACHE . preg_replace('/\.[^.]+$/', '', basename($this->image)) . ".ocr-cache";
        }

        // Check if this is a resized version - if so, prefer pre-resized ocr cache
        if (preg_match('/^(.*)_resized(\.\w+)$/', $this->image, $matches))
        {
            $potential_original = $matches[1] . $matches[2];
            $preferred_cache_file = self::DIR_CACHE . preg_replace('/\.[^.]+$/', '', basename($potential_original)) . ".ocr-cache";
            if (is_file($preferred_cache_file))
            {
                $this->cache_file = $preferred_cache_file;
            }
        }

        return $this->cache_file;
    }

    /**
     * Set image
     */
    function setImage($image)
    {
        if (!is_file($image))
            return false;

        $this->image = $image;

        $this->loadCache();
    }

    /**
     * Set text file
     */
    function setTextFile($text_file)
    {
        if (!is_file($text_file))
            return false;

        $this->text_file = $text_file;
    }

    /**
     * Load Cache if enabled and exists
     */
    function loadCache()
    {
        if ($this->cache and $this->image)
        {
            $cachefile = $this->getCacheFile();
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
            $cachefile = $this->getCacheFile();
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
            // Prefer text file if set
            if (!is_null($this->text_file))
            {
                $this->text = explode(EOL, file_get_contents($this->text_file));
                return $this->text;
            }

            $image = $this->image;

            if (empty($image))
            {
                $this->text = [""];
                return $this->text;
            }

            // Convert the image to make sure tesseract will support it
			// May no longer be needed with pre-save to rotated PNG...
			// Testing out that theory:
			/*
            $command = 'convert "' . $image . '" -density 600 "' . $image . '" 2>&1';
            exec($command, $output, $return);
            if ($return != 0)
            {
                // self::log($output);
                throw new Exception('Image Conversion Error: ' . implode("\n", $output));
            }
			 */

            // Run OCR
            $command = 'tesseract "' . $image . '" stdout 2>&1';
            exec($command, $output, $return);
            if ($return != 0)
            {
                // self::log($output);
                throw new Exception('OCR Error: ' . implode("\n", $output));
            }

            $this->text = $output;
            $this->saveCache();
        }
        return $this->text;
    }

    /**
     * Get Pattern
     */
    function getPattern($patterns, $return_index=0)
    {
        $found = [];
        $text = $this->getText();

        if (!is_array($patterns))
        {
            $patterns = [$patterns];
        }


        foreach ($text as $line)
        {
            $line = trim($line);
            if (empty($line)) continue;

            // echo "$line<br>";
            foreach ($patterns as $pattern)
            {
                $pattern = '~'.$pattern.'~i';
                // echo " --- $pattern<br>";
                if (preg_match_all($pattern, $line, $matches))
                {
                    $match = $matches[$return_index];
                    // echo " *** MATCH: " . implode(" : ", $match) . " ***<br>";
                    $found = array_merge($found, $match);
                }
            }
            // echo "<br>";
        }
        // echo "------------------------------------------------------------------------------------------------------------------<br>";

        if (empty($found))
            return false;

        return $found;
    }

    /**
     * Get dollar amounts
     */
    function getDollars()
    {
        return $this->getPattern('\d+\.\d{2}');
    }

    /**
     * Get date(s)
     */
    function getDates()
    {
        return $this->getPattern(array(
			// 1-1-00 or 01-01-00
            '\b\d{1,2}([-/])\d{1,2}\1\d{2}\b',
			// 1-1-2000 or 01-01-2000
            '\b\d{1,2}([-/])\d{1,2}\1\d{4}\b',
			// 2000-01-01
            '\b\d{4}([/-])\d{2}\1\d{2}\b',
			// January 1 or January 01
			// or January 1 2000 or January 01 2000
			// or January 1, 2000 or January 01, 2000
            '\b('.implode('|', self::$months_short) .')\s*(\d{1,2})?(,?\s*\d{4})?\b',
			// Jan 1 or Jan 01
			// or Jan 1 2000 or Jan 01 2000
			// or Jan 1, 2000 or Jan 01, 2000
            '\b('.implode('|', self::$months_long) .')\s*(\d{1,2})?(,?\s*\d{4})?\b',
        ));
    }
}
