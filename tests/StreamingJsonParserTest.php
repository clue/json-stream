<?php

use Clue\JsonStream\StreamingJsonParser;

class StreamingJsonParserTest extends PHPUnit\Framework\TestCase
{
    private $parser;

    /**
     * @before
     */
    public function setUpParser()
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

    public function testInvalid()
    {
        $this->setExpectedException('UnexpectedValueException');
        $this->parser->push('invalid');
    }

    public function setExpectedException($exception, $exceptionMessage = '', $exceptionCode = null)
    {
        if (method_exists($this, 'expectException')) {
            // PHPUnit 5.2+
            $this->expectException($exception);
            if ($exceptionMessage !== '') {
                $this->expectExceptionMessage($exceptionMessage);
            }
            if ($exceptionCode !== null) {
                $this->expectExceptionCode($exceptionCode);
            }
        } else {
            // legacy PHPUnit 4 - PHPUnit 5.1
            parent::setExpectedException($exception, $exceptionMessage, $exceptionCode);
        }
    }
}
