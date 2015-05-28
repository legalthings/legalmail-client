<?php

namespace LegalThings;

/**
 * Interface to the LegalMail API
 */
class LegalMail
{
    /**
     * The URL to the API
     * @var string
     */
    public static $url;
    
    /**
     * Additional guzzle options
     * @var array
     */
    public static $guzzleOptions = [];
    
    /**
     * Send an email through LegalMail
     * 
     * @param string  $template     Reference of the template
     * @param array   $from         Associative array that contains one 'name' and 'email' property
     * @param array   $to           Associative array that contains multiple 'name' and 'email' properties
     * @param array   $data         Data to fill in the template
     * @param array   $attachments  Associative array containing 'source' and 'filename' properties
     */
    public static function send($template, $from, $to, $data, $attachments = [])
    {
        if (!static::$url) throw new Exception("LegalMail url not configured");
        
        $client = new GuzzleHttp\Client([
            'base_url' => static::$url,
            'defaults' => [
                'headers' => ['Content-Type' => 'application/json']
            ] + static::$guzzleOptions
        ]);

        $payload = [
            'template' => $template,
            'from' => $from,
            'to' => $to,
            'data' => $data,
            'attachments' => $attachments
        ];

        $request = $client->createRequest('POST', '/email');
        $request->setBody(GuzzleHttp\Stream\Stream::factory(json_encode($payload)));
        $client->send($request);
    }
}
