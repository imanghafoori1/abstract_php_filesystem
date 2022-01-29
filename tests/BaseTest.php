<?php

namespace Imanghafoori\SearchReplace\Tests;

use Imanghafoori\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /**
     * @test
     */
    public function removeLine()
    {
        Filesystem::fake();
        $result = Filesystem::removeLine(__DIR__.'/stub/sample.stub', 7);

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");
        $f2 = Filesystem::file_get_contents(__DIR__.'/stub/sample_removed_line.stub', "\n");

        $this->assertEquals($f2, $f1);
        $this->assertEquals($result, true);

        Filesystem::fake();
        $result = Filesystem::removeLine(__DIR__.'/stub/sample.stub', 700);
        $this->assertEquals($result, false);
    }

    /**
     * @test
     */
    public function removeLine2()
    {
        Filesystem::fake();
        Filesystem::removeLine(__DIR__.'/stub/sample_removed_line.stub', 3);

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample_removed_line.stub', "\n");
        $f2 = Filesystem::file_get_contents(__DIR__.'/stub/sample_removed_line2.stub', "\n");

        $this->assertEquals($f2, $f1);
    }

    /**
     * @test
     */
    public function insertLine()
    {
        Filesystem::fake();
        Filesystem::insertNewLine(__DIR__.'/stub/sample.stub', 'Hello', 3);

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");
        $f2 = Filesystem::file_get_contents(__DIR__.'/stub/sample_inserted_hello.stub', "\n");

        $this->assertEquals($f2, $f1);
    }

    /**
     * @test
     */
    public function insertLineBeyondFileLength()
    {
        Filesystem::fake();

        $f2 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");
        $result = Filesystem::insertNewLine(__DIR__.'/stub/sample.stub', 'Hello', 200);

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");

        $this->assertEquals($f2, $f1);
        $this->assertEquals($result, false);
    }

    /**
     * @test
     */
    public function replaceFirst()
    {
        Filesystem::fake();
        $result = Filesystem::replaceFirst(__DIR__.'/stub/sample.stub', 'TestCase', 'Hello');

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
        Filesystem::fake();
        $f2 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");
        $result = Filesystem::replaceFirst(__DIR__.'/stub/sample.stub', 'dsfvsfdv', 'Hello');

        $f1 = Filesystem::file_get_contents(__DIR__.'/stub/sample.stub', "\n");

        $this->assertEquals($f2, $f1);
        $this->assertFalse($result);
    }
}
