<?php

namespace Imanghafoori\Filesystem;

class FileManipulator
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

    public static function removeLine($absPath, $lineNumber = [])
    {
        $lineNumber = (array) $lineNumber;
        $lineChanger = function ($currentLineNum) use ($lineNumber) {
            // Replace only the first occurrence in the file
            if (in_array($currentLineNum, $lineNumber)) {
                return '';
            }

            return null;
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

    public static function insertAtLine($absPath, $newLine, $atLine)
    {
        return self::insertNewLine($absPath, $newLine, $atLine);
    }

    private static function applyToEachLine($absPath, $lineChanger)
    {
        $fs = self::$fileSystem;
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
            try {
                $fs::rename($absPath.$tmp, $absPath);
            } catch (\ErrorException $e) {
                $fs::unlink($absPath.$tmp);
                return false;
            }
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
