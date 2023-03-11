<?php

use PHPUnit\Framework\TestCase;
use \Hood\Toolbox\Test;

class SampleTest extends TestCase
{
    public function test_sample()
    {
        $sample_value = Test::value();
        $this->assertEquals('sample_value', $sample_value);
    }
}
