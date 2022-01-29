<?php

namespace Imanghafoori\FileSystem;

class Filesystem
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

    public static function file_get_content($absPath, $line_endings = null)
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

    public static function removeLine($absPath, $_lineNumber = null)
    {
        $lineChanger = function ($currentLineNum) use ($_lineNumber) {
            // Replace only the first occurrence in the file
            if ($currentLineNum === $_lineNumber) {
                return '';
            }
        };

        return self::applyToEachLine($absPath, $lineChanger);
    }

    public static function replaceFirst($absPath, $search, $replace = '', $_line = null)
    {
        $lineChanger = function ($lineNum, $line, $isReplaced) use ($search, $replace, $_line) {
            // Replace only the first occurrence in the file
            if (! $isReplaced && strstr($line, $search)) {
                if (! $_line || $lineNum === $_line) {
                    return self::replaceFirstInStr($search, $replace, $line);
                }
            }
        };

        return self::applyToEachLine($absPath, $lineChanger);
    }

    public static function insertNewLine($absPath, $newLine, $atLine)
    {
        $lineChanger = function ($lineNum, $currentLine) use ($newLine, $atLine) {
            if ($lineNum === $atLine) {
                return $newLine.PHP_EOL.$currentLine;
            }
        };

        return self::applyToEachLine($absPath, $lineChanger);
    }

    private static function applyToEachLine($absPath, $lineChanger)
    {
        $fs = Filesystem::$fileSystem;
        $reading = $fs::fopen($absPath, 'r');
        $tmp = '_tmpp-'. rand(10000, 99990);
        $tmpFile = $fs::fopen($absPath.$tmp, 'w');

        $isReplaced = false;

        $lineNum = 0;
        while (! $fs::feof($reading)) {
            $lineNum++;
            $line = $fs::fgets($reading);

            $newLine = $lineChanger($lineNum, $line, $isReplaced);
            if (is_string($newLine)) {
                $line = $newLine;
                $isReplaced = true;
            }
            // Copy the entire file to the end
            $fs::fwrite($tmpFile, $line);
        }
        $fs::fclose($reading);
        $fs::fclose($tmpFile);
        // Might as well not overwrite the file if we didn't replace anything
        if ($isReplaced) {
            $fs::rename($absPath.$tmp, $absPath);
        } else {
            $fs::unlink($absPath.$tmp);
        }

        return $isReplaced;
    }

    private static function replaceFirstInStr($search, $replace, $subject)
    {
        $position = strpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }
}
