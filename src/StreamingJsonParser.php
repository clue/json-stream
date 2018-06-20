<?php

namespace Clue\JsonStream;

use UnexpectedValueException;

class StreamingJsonParser
{
    private $buffer = '';
    private $endCharacter = null;

    private $assoc = true;

    /**
     * @param string $chunk
     * @return array
     */
    public function push($chunk)
    {
        $objects = array();

        while ($chunk !== '') {
            if ($this->endCharacter === null) {
                // trim leading whitespace
                $chunk = ltrim($chunk);

                if ($chunk === '') {
                    // only whitespace => skip chunk
                    break;
                } elseif ($chunk[0] === '[') {
                    // array/list delimiter
                    $this->endCharacter = ']';
                } elseif ($chunk[0] === '{') {
                    // object/hash delimiter
                    $this->endCharacter = '}';
                } else {
                    throw new UnexpectedValueException('Invalid start');
                }
            }

            $pos = strpos($chunk, $this->endCharacter);

            // no end found in chunk => must be part of segment, wait for next chunk
            if ($pos === false) {
                $this->buffer .= $chunk;
                break;
            }

            // possible end found in chunk => select possible segment from buffer, keep remaining chunk
            $this->buffer .= substr($chunk, 0, $pos + 1);
            $chunk = substr($chunk, $pos + 1);

            // try to parse
            $json = json_decode($this->buffer, $this->assoc);

            // successfully parsed
            if ($json !== null) {
                $objects [] = $json;

                // clear parsed buffer and continue checking remaining chunk
                $this->buffer = '';
                $this->endCharacter = null;
            }
        }

        return $objects;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return ($this->buffer === '');
    }

    /**
     * @return string
     */
    public function getBuffer()
    {
        return $this->buffer;
    }
}
