<?php declare(strict_types = 1);
/**
 * Router wrapper class
 * @author - Sanket Raut <sanket@digitalbit.in>
 */
namespace SimplePi\Kernel;

use SimplePi\Kernel\Dependencies;
use SimplePi\Http\HttpResponse;
use SimplePi\Http\HttpRequest;

use FastRoute;

class Router {
    /**
     * Common variables
     */
    protected $request;
    protected $requestMethod;
    protected $response;
    protected $collection = [];
    protected $routecollection = [];
    protected $router;
    protected $class;
    protected $method;
    protected $payload;
    protected $routecallback;
    protected $routehandler;
    protected $routevars;
    protected $routebag = [];
    protected $namespace = '\\App\\Controllers\\';
    protected $statusTexts = [
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
        306 => 'Reserved',
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
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Reserved for WebDAV advanced collections expired proposal',
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

    /**
     * Initialize configuration with Fast Route
     */
    public function __construct(HttpRequest $request, HttpResponse $response, Dependencies $dependency) {
        $this->request = $request;
        $this->response = $response;
        $this->dependency = $dependency;
    }

    /**
     * Generate Fast Route and dispatch router
     */
    private function __dispatchRouter() {
        $this->router = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $route) {
            if(!empty($this->routecollection)) {
                foreach($this->routecollection as $r) {
                    $route->addRoute($r['method'],$r['route'],$r['callback']);
                }           
            }
        });
        // setup the collection to dispatch
        $this->__setupDispatch();
    }

    /**
     * setup the collection to dispatch with error handler
     */
    private function __setupDispatch() {
        $this->payload = $this->router->dispatch($this->request->method(),$this->request->path());
        $this->requestMethod = filter_input(INPUT_SERVER,'REQUEST_METHOD');

        // error handler to throw errors
        switch ($this->payload[0]) {            
            case FastRoute\Dispatcher::NOT_FOUND:
                response()->json(['message'=>'404 - '.$this->statusTexts[404]],404);
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                response()->json(['message'=>'405 - '.$this->statusTexts[405]],405);
                break;
            case FastRoute\Dispatcher::FOUND:
                if($this->requestMethod === 'OPTIONS') {
                    return true;
                }
                $this->routehandler = $this->payload[1];
                $this->routevars = $this->payload[2];
                if(is_object($this->routehandler)) {
                    call_user_func($this->routehandler, $this->routevars);
                } else {
                    $this->class = $this->dependency->injector->make($this->payload[1][0]);
                    $this->method = $this->payload[1][1];
                    $this->request->add($this->routevars);
                    $this->class->{$this->method}($this->request,$this->response);
                }
                break;
            default:
                return response()->json(['message'=>'500 - '.$this->statusTexts[500]],500);
                break;
        } 
    }

    /**
     * push route to routecollection array
     */
    private function __pushRoute($method,$route,$routecallback) {
        $this->collection = [
            'method'   => $method,
            'route'    => $route,
            'callback' => $routecallback
        ];
        array_push($this->routecollection,$this->collection);
    }

    /**
     * Render the response based on the method and args
     */
    private function __renderResponse($method,$route,$routecallback) {
        // Preflight requests for every route
        if(!in_array($route,$this->routebag)) {
            array_push($this->routebag,$route);
            $this->__renderResponse('OPTIONS',$route,$routecallback);
        }
        if(gettype($routecallback) == 'string') {
            $this->routecallback = explode('@',$routecallback);
            if(count($this->routecallback) > 1) {
                $this->routecallback[0] = class_exists($this->routecallback[0])?$this->routecallback[0]:$this->namespace . $this->routecallback[0];
                $this->class = $this->routecallback[0];
                $this->method = $this->routecallback[1];

                if(method_exists($this->class,$this->method)) {
                    $this->__pushRoute($method,$route,[$this->class,$this->method]);
                    $this->__pushRoute($method,$route.'/',[$this->class,$this->method]);
                }
            }
        } else {
            $this->__pushRoute($method,$route,$routecallback);
            $this->__pushRoute($method,$route.'/',$routecallback);
        }
    }

    /**
     * Dispatch routes after all of them are ready
     */
    public function dispatchRoutes() {
        $this->__dispatchRouter();
    }

    /**
     * GET routes
     */
    public function get($route, $routecallback) {
        $this->__renderResponse('GET',$route,$routecallback);
    }

    /**
     * POST routes
     */
    public function post($route, $routecallback) {
        $this->__renderResponse('POST',$route,$routecallback);
    }

    /**
     * PUT routes
     */
    public function put($route, $routecallback) {
        $this->__renderResponse('PUT',$route,$routecallback);
    }

}   