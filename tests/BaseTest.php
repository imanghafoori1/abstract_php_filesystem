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
        FileManipulator::removeLine(__DIR__.'/stub/sample.stub', 7);

        $f1 = FileSystem::read_file(__DIR__.'/stub/sample.stub');
        $f2 = FileSystem::read_file(__DIR__.'/stub/sample_removed_line.stub');

        $this->assertEquals($f2, $f1);
    }

    /**
     * @test
     */
    public function removeLine2()
    {
        FileSystem::fake();
        FileManipulator::removeLine(__DIR__.'/stub/sample_removed_line.stub', 3);

        $f1 = FileSystem::read_file(__DIR__.'/stub/sample_removed_line.stub');
        $f2 = FileSystem::read_file(__DIR__.'/stub/sample_removed_line2.stub');

        $this->assertEquals($f2, $f1);
    }
}
