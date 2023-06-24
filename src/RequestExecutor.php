<?php

declare(strict_types=1);

/*
 * This file is auto generated using the drewlabs/mdl UML model class generator package
 *
 * (c) Sidoine Azandrew <contact@liksoft.tg>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Drewlabs\Htr;

use Closure;
use Drewlabs\Curl\REST\Client;
use Drewlabs\Htr\Contracts\RequestInterface;
use Drewlabs\Htr\Contracts\RepositoryInterface;
use Drewlabs\Htr\Compilers\AuthorizationHeaderCompiler;
use Drewlabs\Htr\Compilers\BodyPartCompiler;
use Drewlabs\Htr\Compilers\CookieCompiler;
use Drewlabs\Htr\Compilers\HeaderCompiler;
use Drewlabs\Htr\Compilers\ParamCompiler;
use Drewlabs\Htr\Compilers\URLCompiler;
use Drewlabs\Htr\Contracts\BodyDescriptor;
use Drewlabs\Htr\Contracts\Descriptor;
use Drewlabs\Curl\REST\Contracts\ResponseInterface;
use Drewlabs\Curl\REST\Response;

class RequestExecutor
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Closure(string $method, string $url, array $body, array $headers, array $cookies):void
     */
    private $beforeRequest;

    /**
     * @var array<string,string>
     */
    const DEFAULT_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => '*/*',
        'Connection' => 'keep-alive',
        'User-Agent' => 'HtrRuntime/v0.1.0'
    ];


    /**
     * Creates new class instance
     * 
     * @param RequestInterface $request 
     * @return RequestExecutor 
     */
    public static function new(RequestInterface $request)
    {
        $instance = new self;
        $instance->setRequest($request);
        return $instance;
    }

    /**
     * Set a callback to execute before sending request
     * 
     * @param Closure(string $method, string $url, array $headers, array $cookies):void $before 
     * 
     * @return self
     */
    public function before(\Closure $before)
    {
        $this->beforeRequest = $before;
        return $this;
    }

    /**
     * Execute the request and return the execution result
     * 
     * @param RepositoryInterface $env
     *
     * @return Response&ResponseInterface
     */
    public function execute(RepositoryInterface $env)
    {
        # code...
        $url = URLCompiler::new($env)->compile($this->request->getUrl());
        $method = $this->request->getMethod() ?? 'GET';
        $headers = array_map(function (Descriptor $header) use ($env) {
            return HeaderCompiler::new($env)->compile($header);
        }, $this->request->getHeaders() ?? []);
        // TODO : Add default headers
        $headers = array_merge(self::DEFAULT_HEADERS, (null !== ($authorization = $this->request->getAuthorization())) ? AuthorizationHeaderCompiler::new($env)->compile($authorization) : [], ...$headers);

        // #region Prepare request  query params
        $params = array_map(function (Descriptor $param) use ($env) {
            return ParamCompiler::new($env)->compile($param);
        }, $this->request->getParams() ?? []);
        $params = array_merge(...$params);
        // #endregion Prepare request query params

        // #region Prepare request body
        $body = array_map(function (BodyDescriptor $param) use ($env) {
            return BodyPartCompiler::new($env)->compile($param);
        }, $this->request->getBody() ?? []);
        $body = array_merge(...$body);
        // #endregion Prepare request body

        // #region Prepare request cookies
        $cookies = array_map(function (Descriptor $param) use ($env) {
            return CookieCompiler::new($env)->compile($param);
        }, $this->request->getCookies() ?? []);
        $cookies = array_merge(...$cookies);
        // #endregion Prepare request cookies

        // TODO: Send the request and return response
        if ($this->beforeRequest) {
            ($this->beforeRequest)($method, $url, $body, $headers, $cookies);
        }
        return Client::new([CURLOPT_USERAGENT => 'HtrRuntime/v0.1.0'])
            ->prepareRequest([
                'params' => $params,
                'cookies' => $cookies,
                'headers' => $headers
            ])
            ->setMethod($method)
            ->setRequestURI($url)
            ->sendRequest($body);
    }

    /**
     * Set request property value
     * 
     * @param RequestInterface $value
     *
     * @return self
     */
    public function setRequest(RequestInterface $value)
    {
        # code...
        $this->request = $value;

        return $this;
    }

    /**
     * Get request property value
     * 
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        # code...
        return $this->request;
    }
}
