<?php

namespace Imanghafoori\Filesystem;

class FakeFilesystem
{
    public static $putContent = [];

    public static $files = [];

    public static $pointers = [];

    public static function reset()
    {
        self::$putContent = [];
        self::$files = [];
        self::$pointers = [];
    }

    public static function file_put_contents($absPath, $newVersion)
    {
        self::$putContent[$absPath] = $newVersion;
    }

    public static function feof($stream)
    {
        $i = self::$pointers[$stream];

        return ! isset(self::$files[$stream][$i]);
    }

    public static function fopen($filename, $mode = 'r')
    {
        $lines = is_file($filename) ? file($filename) : [];

        self::$files[$filename] = $lines;
        self::$pointers[$filename] = 0;

        return $filename;
    }

    public static function fgets($stream)
    {
        $i = self::$pointers[$stream];
        $val = (self::$files[$stream][$i]);
        self::$pointers[$stream]++;

        return $val;
    }

    public static function fwrite($stream, $data)
    {
        return self::$files[$stream][] = $data;
    }

    public static function rename($from, $to)
    {
        self::$files[$to] = self::$files[$from];

        unset(self::$files[$from]);
    }

    public static function unlink($filename)
    {
        unset(self::$files[$filename]);
        unset(self::$pointers[$filename]);
    }

    public static function fclose($filename)
    {
        //unset(self::$files[$filename]);
        //unset(self::$pointers[$filename]);
    }

    public static function removeLine($absPath, $_lineNumber = null)
    {
        self::fopen($absPath);

        $count = count(self::$files[$absPath]);

        if ($count < $_lineNumber) {
            return false;
        }

        unset(self::$files[$absPath][$_lineNumber - 1]);

        // Re-index the array elements
        self::$files[$absPath] = array_values(self::$files[$absPath]);

        return true;
    }

    public static function replaceFirst($absPath, $search, $replace = '', $_line = null)
    {

    }

    public static function insertNewLine($absPath, $newLine, $atLine)
    {
        self::fopen($absPath);

        $count = count(self::$files[$absPath]);

        if ($count < $atLine) {
            return false;
        }

        array_splice(self::$files[$absPath], $atLine - 1, 0, [$newLine.PHP_EOL]);
    }
}
