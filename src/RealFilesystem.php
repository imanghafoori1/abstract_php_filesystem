<?php

namespace Imanghafoori\Filesystem;

class RealFilesystem
{
    public static function __callStatic($method, $params)
    {
        $methods = [
            'file_get_contents',
            'file_put_contents',
            'feof',
            'fopen',
            'fgets', 'fgetc',
            'fwrite',
            'rename',
            'unlink',
            'mkdir',
            'rmdir',
            'ftruncate',
            'fflush',
            'touch',
            'fclose',
        ];

        if (in_array($method, $methods, true)) {
            try {
                return $method(...$params);
            } catch (\ErrorException $e) {
                //
            }
        }
    }
}
