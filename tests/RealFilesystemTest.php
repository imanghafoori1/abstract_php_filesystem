<?php

namespace Imanghafoori\SearchReplace\Tests;

use Imanghafoori\Filesystem\Filesystem;
use Imanghafoori\Filesystem\FileManipulator;
use PHPUnit\Framework\TestCase;

class RealFilesystemTest extends TestCase
{
    /**
     * @test
     */
    public function removeLine()
    {
        FileManipulator::fake();
        $result = FileManipulator::removeLine(__DIR__.'/stub/sample.stub', 7);

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");

        $f2 = file_get_contents(__DIR__.'/stub/sample_removed_line.stub', "\n");
        $f2 = str_replace(["\r\n"], "\n", $f2);

        $this->assertEquals($f2, $f1);
        $this->assertEquals($result, true);

        FileManipulator::fake();
        $result = FileManipulator::removeLine(__DIR__.'/stub/sample.stub', 17);
        $this->assertEquals($result, false);

        FileManipulator::fake();
        $result = FileManipulator::removeLine(__DIR__.'/stub/sample.stub', 16);
        $this->assertEquals($result, true);
    }

    /**
     * @test
     */
    public function removeLine2()
    {
        FileManipulator::fake();
        FileManipulator::removeLine(__DIR__.'/stub/sample_removed_line.stub', 3);

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample_removed_line.stub', "\n");
        $f2 = file_get_contents(__DIR__.'/stub/sample_removed_line2.stub', "\n");
        $f2 = str_replace(["\r\n"], "\n", $f2);

        $this->assertEquals($f2, $f1);
    }

    /**
     * @test
     */
    public function removeLine3()
    {
        FileManipulator::fake();
        FileManipulator::removeLine(__DIR__.'/stub/sample.stub', [3, 7]);

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");
        $f2 = file_get_contents(__DIR__.'/stub/sample_removed_line2.stub', "\n");
        $f2 = str_replace(["\r\n"], "\n", $f2);

        $this->assertEquals($f2, $f1);
    }

    /**
     * @test
     */
    public function insertLine()
    {
        FileManipulator::fake();
        FileManipulator::insertNewLine(__DIR__.'/stub/sample.stub', 'Hello', 3);

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");
        $f2 = Filesystem::file_get_contents(__DIR__.'/stub/sample_inserted_hello.stub', "\n");

        $this->assertEquals($f2, $f1);
    }

    /**
     * @test
     */
    public function insertLineBeyondFileLength()
    {
        FileManipulator::fake();

        $f2 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");
        $result = FileManipulator::insertNewLine(__DIR__.'/stub/sample.stub', 'Hello', 17);

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");

        $this->assertEquals($f2, $f1);
        $this->assertEquals($result, false);
    }

    /**
     * @test
     */
    public function replaceFirst()
    {
        FileManipulator::fake();
        $result = FileManipulator::replaceFirst(__DIR__.'/stub/sample.stub', 'TestCase', 'Hello');

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");
        $f2 = Filesystem::file_get_contents(__DIR__.'/stub/replacedFirst.stub', "\n");

        $this->assertEquals($f2, $f1);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function replaceFirstFail()
    {
        FileManipulator::fake();
        $f2 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");
        $result = FileManipulator::replaceFirst(__DIR__.'/stub/sample.stub', 'dsfvsfdv', 'Hello');

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");

        $this->assertEquals($f2, $f1);
        $this->assertFalse($result);
    }
}
