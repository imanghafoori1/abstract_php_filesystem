<?php

namespace Imanghafoori\FileSystem;

class RealFileSystem
{
    public static function __callStatic($method, $params)
    {
        $methods = [
            'file_put_contents',
            'feof',
            'fopen',
            'fgets',
            'fwrite',
            'rename',
            'unlink',
            'close',
        ];

        if (in_array($method, $methods, true)) {
            return $method(...$params);
        }
    }
}
