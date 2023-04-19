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

use Drewlabs\Htr\Contracts\RequestInterface;
use Drewlabs\Htr\Contracts\RepositoryInterface;
use Drewlabs\Htr\Compilers\AuthorizationHeaderCompiler;
use Drewlabs\Htr\Compilers\RequestBodyPartCompiler;
use Drewlabs\Htr\Compilers\RequestCookieCompiler;
use Drewlabs\Htr\Compilers\RequestHeaderCompiler;
use Drewlabs\Htr\Compilers\RequestParamCompiler;
use Drewlabs\Htr\Compilers\RequestURLCompiler;
use Drewlabs\Htr\Contracts\BodyDescriptor;
use Drewlabs\Htr\Contracts\Descriptor;

class RequestExecutor
{

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var array<string,string>
     */
    const DEFAULT_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => '*/*',
        'Connection' => 'keep-alive',
        'User-Agent' => 'Htr/v0.1.0',
        'Accept-Encoding' => 'gzip, deflate, br'
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
     * Execute the request and return the execution result
     * 
     * @param RepositoryInterface $env
     *
     * @return mixed
     */
    public function execute(RepositoryInterface $env)
    {
        # code...
        $url = RequestURLCompiler::new($env)->compile($this->request->getUrl());
        $method = $this->request->getMethod() ?? 'GET';
        $headers = array_map(function (Descriptor $header) use ($env) {
            return RequestHeaderCompiler::new($env)->compile($header);
        }, $this->request->getHeaders() ?? []);
        // TODO : Add default headers
        $headers = array_merge(self::DEFAULT_HEADERS, (null !== ($authorization = $this->request->getAuthorization())) ? AuthorizationHeaderCompiler::new($env)->compile($authorization) : [], ...$headers);

        // #region Prepare request  query params
        $params = array_map(function (Descriptor $param) use ($env) {
            return RequestParamCompiler::new($env)->compile($param);
        }, $this->request->getParams() ?? []);
        $params = array_merge(...$params);
        // #endregion Prepare request query params

        // #region Prepare request body
        $body = array_map(function (BodyDescriptor $param) use ($env) {
            return RequestBodyPartCompiler::new($env)->compile($param);
        }, $this->request->getBody() ?? []);
        $body = array_merge(...$body);
        // #endregion Prepare request body

        // #region Prepare request cookies
        $cookies = array_map(function (Descriptor $param) use ($env) {
            return RequestCookieCompiler::new($env)->compile($param);
        }, $this->request->getCookies() ?? []);
        $cookies = array_merge(...$cookies);
        // #endregion Prepare request cookies

        print_r([$url, $method, $headers, $params, $body, $cookies]);
        // TODO: Send the request and return response
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
