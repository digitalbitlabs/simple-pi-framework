<?php declare(strict_types = 1);
/**
 * Http Response class to handle response
 * @author: Sanket Raut
 */

namespace SimplePi\Http;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class HttpResponse {

    protected $response;
    protected $content;

    /**
     * Initialize the response object
     */
    public function __construct() {
        $this->response = new Response(
            'Content',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    /**
     * Get response status code
     */
    public function status(){
        return $this->response->getStatusCode();   
    }

    /**
     * Response for plain text content
     */
    public function html($content,$statusCode = 200) {
        $this->response->setContent($content);
        $this->response->setStatusCode($statusCode);
        $this->response->send();
    }

    /**
     * Response for json content
     */
    public function json($content,$statusCode = 200) {
        $this->response = new JsonResponse($content, $statusCode);
        $this->response->setStatusCode($statusCode);
        $this->response->send();
    }

}