<?php

use Clue\JsonStream\StreamingJsonParser;

class StreamingJsonParserTest extends TestCase
{
    private $parser;

    public function setUp()
    {
        $this->parser = new StreamingJsonParser();
    }

    public function testEmptyObject()
    {
        $this->assertEquals(array(array()), $this->parser->push('{}'));
    }

    public function testEmptyArray()
    {
        $this->assertEquals(array(array()), $this->parser->push('[]'));
    }

    public function testWhitespaceOnly()
    {
        $this->assertEquals(array(), $this->parser->push('  '));
        $this->assertTrue($this->parser->isEmpty());
    }

    public function testIncrementalParsingSingle()
    {
        $this->assertEquals(array(), $this->parser->push('['));
        $this->assertEquals(array(), $this->parser->push('['));
        $this->assertEquals(array(), $this->parser->push('true'));
        $this->assertEquals(array(), $this->parser->push(']'));
        $this->assertFalse($this->parser->isEmpty());

        $this->assertEquals(array(array(array(true))), $this->parser->push(']'));
        $this->assertTrue($this->parser->isEmpty());
    }

    public function testMultipleObjects()
    {
        $this->assertEquals(array(array(), array()), $this->parser->push('{}{}'));
        $this->assertTrue($this->parser->isEmpty());
    }

    public function testMultipleObjectsWhitespace()
    {
        $this->assertEquals(array(array(), array()), $this->parser->push(' { } { } '));
        $this->assertTrue($this->parser->isEmpty());
    }

    public function testMultipleObjectAndArray()
    {
        $this->assertEquals(array(array(), array()), $this->parser->push('{}[]'));
        $this->assertTrue($this->parser->isEmpty());
    }

    public function testGetBuffer()
    {
        $this->parser->push('{ "id": 1, "name":');
        $this->assertEquals('{ "id": 1, "name":', $this->parser->getBuffer());
        $this->parser->push('"first" }{ "id": 3, "name":');
        $this->assertEquals('{ "id": 3, "name":', $this->parser->getBuffer());
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalid()
    {
        $this->parser->push('invalid');
    }
}
