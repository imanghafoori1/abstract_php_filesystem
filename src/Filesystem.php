<?php

namespace Imanghafoori\Filesystem;

class Filesystem
{
    public static $fileSystem = RealFilesystem::class;

    public static function fake()
    {
        FakeFilesystem::reset();
        self::$fileSystem = FakeFilesystem::class;
    }

    public static function unfake()
    {
        FakeFilesystem::reset();
        self::$fileSystem = RealFilesystem::class;
    }

    public static function __callStatic($method, $params)
    {
        $methods = [
           'replaceFirst',
           'removeLine',
           'insertNewLine',

        ];

        if (in_array($method, $methods, true)) {
            return self::$fileSystem::$method(...$params);
        }
    }

    public static function changeLineEndings($fileAsArray, $line_endings)
    {
        $result = '';
        foreach ($fileAsArray as $line) {
            $result .= str_replace(["\r\n", "\n", "\r"], $line_endings, $line);
        }

        return $result;
    }

    public static function file_get_contents($absPath, $line_endings = null)
    {
        if (isset(LineManipulator::$fileSystem::$putContent[$absPath])) {
            $content = LineManipulator::$fileSystem::$putContent[$absPath];
            $line_endings && $content = str_replace(["\r\n", "\n", "\r"], $line_endings, $content);

            return $content;
        }

        if (isset(LineManipulator::$fileSystem::$files[$absPath])) {
            if (! in_array($line_endings, ["\r\n", "\n", "\r"], true)) {
                return implode('', LineManipulator::$fileSystem::$files[$absPath]);
            }

            return self::changeLineEndings(LineManipulator::$fileSystem::$files[$absPath], $line_endings);
        }

        if (file_exists($absPath)) {
            return $line_endings ? self::changeLineEndings(file($absPath), $line_endings) : file_get_contents($absPath);
        }
    }
}
