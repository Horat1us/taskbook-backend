<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 1:23 PM
 */

namespace Horat1us\TaskBook;


use Symfony\Component\HttpFoundation\Response;

class CorsResponse extends Response
{
    /**
     * CorsResponse constructor.
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct($content = '', $status = 200, array $headers = [])
    {
        $headers += [
            'Origin' => ['*'],
            'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
            'Access-Control-Request-Headers' => ['*'],
            'Access-Control-Allow-Credentials' => null,
            'Access-Control-Max-Age' => 86400,
        ];
        parent::__construct($content, $status, $headers);
    }
}