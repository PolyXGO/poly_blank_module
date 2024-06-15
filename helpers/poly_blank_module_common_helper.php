<?php

defined('BASEPATH') or exit('No direct script access allowed');

class poly_blank_module_common_helper
{
    public static function core_version()
    {
        $CI = &get_instance();
        return $CI->app_css->core_version();
    }
    
    public static function get_assets($path, $is_version = true, $is_date = false){
        $url = base_url("modules/".POLY_BLANK_MODULE."/assets/{$path}");
        if($is_version){
            $url = self::addOrUpdateUrlParam($url,array('c'=>self::core_version(),'v' => POLY_BLANK_MODULE_VERSION));
        }
        if($is_date){
            $url = self::addOrUpdateUrlParam($url,array('d'=> time()));
        }
        return $url;
    }

    public static function get_assets_minified($path, $is_version = true, $is_date = false)
    {
        $url = base_url($path);
        if ($is_version) {
            $url = self::addOrUpdateUrlParam($url, array('c' => self::core_version(), 'v' => POLY_UTILITIES_VERSION));
        }
        if ($is_date) {
            $url = self::addOrUpdateUrlParam($url, array('d' => time()));
        }
        return self::convertToMinifiedUrl($url);
    }

    public static function convertToMinifiedUrl($url)
    {
        $parts = parse_url($url);
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        $check_localhost = poly_utilities_common_helper::is_localhost();
        if (!$check_localhost) {
            if (substr($parts['path'], -4) === '.css') {
                $parts['path'] = substr($parts['path'], 0, -4) . '.min.css';
            } elseif (substr($parts['path'], -3) === '.js') {
                $parts['path'] = substr($parts['path'], 0, -3) . '.min.js';
            }
        }
        return $parts['path'] . $query;
    }
    public static function removeUrlParam($url, $paramToRemove)
    {
        $parsedUrl = parse_url($url);
        $query = array();

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
        }

        unset($query[$paramToRemove]);

        $newQueryString = http_build_query($query);

        $finalUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        if (isset($parsedUrl['path'])) {
            $finalUrl .= $parsedUrl['path'];
        }
        if ($newQueryString) {
            $finalUrl .= '?' . $newQueryString;
        }

        return $finalUrl;
    }


    public static function addOrUpdateUrlParam($url, $newParams)
    {
        $parsedUrl = parse_url($url);
        $query = array();

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
        }

        $query = array_merge($query, $newParams);

        $newQueryString = http_build_query($query);

        $finalUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        if (isset($parsedUrl['path'])) {
            $finalUrl .= $parsedUrl['path'];
        }
        if ($newQueryString) {
            $finalUrl .= '?' . $newQueryString;
        }

        return $finalUrl;
    }

    /**
     * Requires one file into another file.
     * 
     * The function creates a require statement using a template and places it at a specified location in the target file. If no location is provided, it adds the statement to the file's end.
     * 
     * @param string $destPath      The path to the destination file.
     * @param string $requirePath   The path to the file to require.
     * @param boolean $force        Insert content regardless of whether it exists.
     * @param boolean $position     Location for inserting the require statement. If set to False, append it to the end of the file.
     * 
     * @return mixed
     */
    public static function require_in_file($destPath, $requirePath, $force = false, $position = false)
    {
        if (!file_exists($destPath)) {
            poly_blank_module_common_helper::file_put_contents($destPath, "<?php defined('BASEPATH') or exit('No direct script access allowed');\n");
        }

        if (file_exists($destPath)) {
            $content = file_get_contents($destPath);
            $template = poly_blank_module_common_helper::require_in_file_template($requirePath);

            $exist = preg_match(poly_blank_module_common_helper::require_signature($requirePath), $content);
            if ($exist && !$force) {
                return;
            }
            $content = poly_blank_module_common_helper::unrequire_in_file($destPath, $requirePath);

            if ($position !== false) {
                $content = substr_replace($content, $template . "\n", $position, 0);
            } else {
                $content = $content . $template;
            }

            poly_blank_module_common_helper::file_put_contents($destPath, $content);
        }
    }

    /**
     * Removes a file's require statement from another file.
     * 
     * This function deletes a require statement, which was created using a template, from a specified position in the target file. If no specific position is provided, the function will search for and remove the require statement from the end of the file.
     * 
     * @param string $destPath      The path to the target file.
     * @param string $requirePath   The path to the file whose require statement needs to be removed.
     * 
     * @return string The modified content of the destination file.
     */
    public static function unrequire_in_file($destPath, $requirePath)
    {
        if (file_exists($destPath)) {
            $content = file_get_contents($destPath);
            $content = preg_replace(poly_blank_module_common_helper::require_signature($requirePath), '', $content);
            poly_blank_module_common_helper::file_put_contents($destPath, $content);
            return $content;
        }
    }

    public static function require_signature($file)
    {
        $basename = str_ireplace(['"', "'"], '', basename($file));
        return "#//".POLY_BLANK_MODULE.":start:" . $basename . "([\s\S]*)//" . POLY_BLANK_MODULE . ":end:" . $basename . "#";
    }

    public static function require_in_file_template($path)
    {
        $template = "\n//" . POLY_BLANK_MODULE . ":start:#filename\n//Do not delete or modify the code in this block\nif (file_exists(#path)) {require_once(#path);}\n//END: Do not delete or modify the code in this block\n//".POLY_BLANK_MODULE.":end:#filename";

        $template = str_ireplace('#filename', str_ireplace(['"', "'"], '', basename($path)), $template);
        $template = str_ireplace('#path', $path, $template);
        return $template;
    }

    public static function file_put_contents($path, $content)
    {
        @chmod($path, FILE_WRITE_MODE);
        if (!$fp = fopen($path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
            return false;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $content, strlen($content));
        flock($fp, LOCK_UN);
        fclose($fp);
        @chmod($path, FILE_READ_MODE);
        return true;
    }

    public static function createTab($slug, $name, $view, $position, $icon)
        {
            return [
                "slug" => $slug,
                "name" => $name,
                "view" => $view,
                "position" => $position,
                "icon" => $icon,
                "href" => "#",
                "badge" => [],
                "children" => []
            ];
        }
}