<?php

namespace Imanghafoori\SearchReplace\Tests;

use Imanghafoori\FileSystem\FileManipulator;
use Imanghafoori\FileSystem\FileSystem;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /**
     * @test
     */
    public function removeLine()
    {
        FileSystem::fake();
        $result = FileSystem::removeLine(__DIR__.'/stub/sample.stub', 7);

        $f1 = FileSystem::file_get_content(__DIR__.'/stub/sample.stub', "\n");
        $f2 = FileSystem::file_get_content(__DIR__.'/stub/sample_removed_line.stub', "\n");

        $this->assertEquals($f2, $f1);
        $this->assertEquals($result, true);

        FileSystem::fake();
        $result = FileSystem::removeLine(__DIR__.'/stub/sample.stub', 700);
        $this->assertEquals($result, false);
    }

    /**
     * @test
     */
    public function removeLine2()
    {
        FileSystem::fake();
        FileSystem::removeLine(__DIR__.'/stub/sample_removed_line.stub', 3);

        $f1 = FileSystem::file_get_content(__DIR__.'/stub/sample_removed_line.stub', "\n");
        $f2 = FileSystem::file_get_content(__DIR__.'/stub/sample_removed_line2.stub', "\n");

        $this->assertEquals($f2, $f1);
    }

    /**
     * @test
     */
    public function insertLine()
    {
        FileSystem::fake();
        FileSystem::insertNewLine(__DIR__.'/stub/sample.stub', 'Hello', 3);

        $f1 = FileSystem::file_get_content(__DIR__.'/stub/sample.stub', "\n");
        $f2 = FileSystem::file_get_content(__DIR__.'/stub/sample_inserted_hello.stub', "\n");

        $this->assertEquals($f2, $f1);
    }

    /**
     * @test
     */
    public function insertLine2()
    {
        FileSystem::fake();
        FileSystem::insertNewLine(__DIR__.'/stub/sample.stub', 'Hello', 20);

        $f1 = FileSystem::file_get_content(__DIR__.'/stub/sample.stub', "\n");
        $f2 = FileSystem::file_get_content(__DIR__.'/stub/sample.stub', "\n");

        $this->assertEquals($f2, $f1);
    }
}
