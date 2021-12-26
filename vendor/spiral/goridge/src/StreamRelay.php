<?php

/**
 * Dead simple, high performance, drop-in bridge to Golang RPC with zero dependencies
 *
 * @author Wolfy-J
 */

declare(strict_types=1);

namespace Spiral\Goridge;

use Spiral\Goridge\Exception\HeaderException;
use Spiral\Goridge\Exception\InvalidArgumentException;
use Spiral\Goridge\Exception\RelayException;
use Spiral\Goridge\Exception\TransportException;

/**
 * Communicates with remote server/client over streams using byte payload:
 *
 * [ prefix       ][ payload                               ]
 * [ 1+8+8 bytes  ][ message length|LE ][message length|BE ]
 *
 * prefix:
 * [ flag       ][ message length, unsigned int 64bits, LittleEndian ]
 */
class StreamRelay extends Relay
{
    /**
     * @var resource
     */
    private $in;

    /**
     * @var resource
     */
    private $out;

    /**
     * Example:
     * $relay = new StreamRelay(STDIN, STDOUT);
     *
     * @param resource $in  Must be readable.
     * @param resource $out Must be writable.
     *
     * @throws InvalidArgumentException
     */
    public function __construct($in, $out)
    {
        if (!\is_resource($in) || \get_resource_type($in) !== 'stream') {
            throw new InvalidArgumentException('Expected a valid input resource stream');
        }

        if (!$this->assertReadable($in)) {
            throw new InvalidArgumentException('Input resource stream must be readable');
        }

        if (!is_resource($out) || get_resource_type($out) !== 'stream') {
            throw new InvalidArgumentException('Expected a valid output resource stream');
        }

        if (!$this->assertWritable($out)) {
            throw new Exception\InvalidArgumentException('Output resource stream must be writable');
        }

        $this->in = $in;
        $this->out = $out;
    }

    /**
     * @return Frame
     * @throws RelayException
     */
    public function waitFrame(): Frame
    {
        \error_clear_last();
        $header = @\fread($this->in, 12);

        if ($header === false) {
            throw new HeaderException('Unable to read frame header: ' . $this->getLastErrorMessage());
        }

        if (\strlen($header) !== 12) {
            throw new HeaderException('Unable to read frame header: Incorrect header size');
        }

        $parts = Frame::readHeader($header);

        // total payload length
        $payload = '';
        $length = $parts[1] * 4 + $parts[2];

        while ($length > 0) {
            \error_clear_last();
            $buffer = @\fread($this->in, $length);

            if ($buffer === false) {
                $message = \vsprintf('An error occurred while reading payload from the stream: %s', [
                    $this->getLastErrorMessage(),
                ]);

                throw new TransportException($message);
            }

            $payload .= $buffer;
            $length -= \strlen($buffer);
        }

        return Frame::initFrame($parts, $payload);
    }

    /**
     * @return string
     */
    private function getLastErrorMessage(): string
    {
        $last = (array)\error_get_last();

        return $last['message'] ?? 'Unknown Error';
    }

    /**
     * @param Frame $frame
     */
    public function send(Frame $frame): void
    {
        $body = Frame::packFrame($frame);

        \error_clear_last();
        if (@\fwrite($this->out, $body, \strlen($body)) === false) {
            $message = \vsprintf('An error occurred while write payload to the stream: %s', [
                $this->getLastErrorMessage(),
            ]);

            throw new TransportException($message);
        }
    }

    /**
     * Checks if stream is readable.
     *
     * @param resource $stream
     *
     * @return bool
     */
    private function assertReadable($stream): bool
    {
        $meta = \stream_get_meta_data($stream);

        $available = ['r', 'rb', 'r+', 'rb+', 'w+', 'wb+', 'w+b', 'a+', 'ab+', 'x+', 'c+', 'cb+'];

        return \in_array($meta['mode'], $available, true);
    }

    /**
     * Checks if stream is writable.
     *
     * @param resource $stream
     *
     * @return bool
     */
    private function assertWritable($stream): bool
    {
        $meta = \stream_get_meta_data($stream);

        return !\in_array($meta['mode'], ['r', 'rb'], true);
    }
}
