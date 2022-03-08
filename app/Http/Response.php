<?php

namespace App\Http;

class Response
{
    private $httpCode = 200;
    private $headers = [];
    private $contentType = 'application/json';
    private $content;

    
    public function __construct($httpCode, $content, $contentType = 'application/json')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }


    private function sendHeaders()
    {
        http_response_code($this->httpCode);

        foreach ($this->headers as $key => $value) 
        {
            header($key . ': ' . $value);
        }
    }

    
    public function sendResponse()
    {
        $this->sendHeaders();

        switch ($this->contentType) 
        {
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;
            default:
                echo $this->content;
                break;
        }
    }
}
