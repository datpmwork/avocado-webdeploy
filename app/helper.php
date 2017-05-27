<?php
/**
 * Created by csepmdat.
 * Date: 5/27/2017
 * Time: 12:22 PM
 */
if (!function_exists('chownr')) {
    function chownr($path, $owner)
    {
        if (!is_dir($path))
            return chown($path, $owner);

        $dh = opendir($path);
        while (($file = readdir($dh)) !== false)
        {
            if($file != '.' && $file != '..')
            {
                $fullpath = $path.'/'.$file;
                if(is_link($fullpath))
                    return FALSE;
                elseif(!is_dir($fullpath) && !chown($fullpath, $owner))
                    return FALSE;
                elseif(!chownr($fullpath, $owner))
                    return FALSE;
            }
        }

        closedir($dh);

        if(chown($path, $owner))
            return TRUE;
        else
            return FALSE;
    }
}

if (!function_exists('chgrpr')) {
    function chgrpr($path, $group)
    {
        if (!is_dir($path))
            return chgrp($path, $group);

        $dh = opendir($path);
        while (($file = readdir($dh)) !== false)
        {
            if($file != '.' && $file != '..')
            {
                $fullpath = $path.'/'.$file;
                if(is_link($fullpath))
                    return FALSE;
                elseif(!is_dir($fullpath) && !chgrp($fullpath, $group))
                    return FALSE;
                elseif(!chgrpr($fullpath, $group))
                    return FALSE;
            }
        }

        closedir($dh);

        if(chgrp($path, $group))
            return TRUE;
        else
            return FALSE;
    }
}

if (!function_exists('chmodr')) {
    function chmodr($path, $filemode)
    {
        if (!is_dir($path))
            return chmod($path, $filemode);

        $dh = opendir($path);
        while (($file = readdir($dh)) !== false)
        {
            if($file != '.' && $file != '..')
            {
                $fullpath = $path.'/'.$file;
                if(is_link($fullpath))
                    return FALSE;
                elseif(!is_dir($fullpath) && !chmod($fullpath, $filemode))
                    return FALSE;
                elseif(!chmodr($fullpath, $filemode))
                    return FALSE;
            }
        }

        closedir($dh);

        if(chmod($path, $filemode))
            return TRUE;
        else
            return FALSE;
    }
}