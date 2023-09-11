<?php

namespace Hill;

//
// Ответ.
//
class Response
{
    /**
     * 
     */
    private $body;

    /**
     * 
     */
    private $headers;

    public static $codes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',

        226 => 'IM Used',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',

        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',

        426 => 'Upgrade Required',

        428 => 'Precondition Required',
        429 => 'Too Many Requests',

        431 => 'Request Header Fields Too Large',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',

        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    private $status = 200;

    private $sent = false;

    /**
     * 
     */
    public function __construct($body, array $headers = [])
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->cookies = [];
    }

    public function status($code = null)
    {
        if ($code == null) {
            return $this->status;
        }

        if (!array_key_exists($code, self::$codes)) {
            throw new \InvalidArgumentException("Invalid status code");
        }

        $this->status = $code;
    }

    /**
     * 
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function write($data)
    {
        $this->body .= $data;
    }

    public function clear()
    {
        $this->body = null;
        $this->status = 200;
        $this->headers = [];
    }
    
    public function sent()
    {
        return $this->sent;
    }

    /**
     * 
     */
    public function send()
    {
        if (ob_get_length() > 0) {
            ob_end_clean();
        }

        if (!headers_sent()) {
            header(
                sprintf(
                    '%s %d %s',
                    $_SERVER['SERVER_PROTOCOL'],
                    $this->status,
                    self::$codes[$this->status]
                ),
                true,
                $this->status
            );

            foreach ($this->headers as $header => $value) {
                header(sprintf(
                    "%s: %s",
                    $header,
                    $value
                ));
            }
        }

        echo $this->body;

        $this->sent = true;
    }
}
