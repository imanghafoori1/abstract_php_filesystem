<?php

namespace Imanghafoori\FileSystem;

class FileSystem
{
    public static $fileSystem = RealFileSystem::class;

    public static function fake()
    {
        FakeFileSystem::reset();
        self::$fileSystem = FakeFileSystem::class;
    }

    public static function changeLineEndings($fileAsArray, $line_endings)
    {
        $result = '';
        foreach ($fileAsArray as $line) {
            $result .= str_replace(["\r\n", "\n", "\r"], $line_endings, $line);
        }

        return $result;
    }

    public static function read_file($absPath, $line_endings = null)
    {
        if (isset(self::$fileSystem::$putContent[$absPath])) {
            $content = self::$fileSystem::$putContent[$absPath];
            $line_endings && $content = str_replace(["\r\n", "\n", "\r"], $line_endings, $content);

            return $content;
        }

        if (isset(self::$fileSystem::$files[$absPath])) {
            if (! in_array($line_endings, ["\r\n", "\n", "\r"], true)) {
                return implode('', self::$fileSystem::$files[$absPath]);
            }

            return self::changeLineEndings(self::$fileSystem::$files[$absPath], $line_endings);
        }

        if (file_exists($absPath)) {
            return $line_endings ? self::changeLineEndings(file($absPath), $line_endings) : file_get_contents($absPath);
        }
    }
}
