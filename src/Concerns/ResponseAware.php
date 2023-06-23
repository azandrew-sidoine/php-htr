<?php

namespace Drewlabs\Htr\Concerns;

use Drewlabs\Htr\BodyPart;
use Drewlabs\Htr\Exceptions\AssertionException;
use Drewlabs\Htr\Header;
use Drewlabs\Htr\Utilities\Assert;
use Drewlabs\Htr\Utilities\PrepareDescriptors;

trait ResponseAware
{
    /**
     * @var array<array-key, \Drewlabs\Htr\Header>
     */
    private $responseHeaders;

    /**
     * @var array<array-key, \Drewlabs\Htr\BodyPart>
     */
    private $responseBody;

    /**
     * Set response headers value
     * 
     * @param array $values 
     * @return $this 
     * @throws AssertionException 
     */
    public function setResponseHeaders(array $values)
    {
        $values = (new PrepareDescriptors)->call($values);
		Assert::assertIsArrayOfArray($values);
		$this->responseHeaders = array_map(function ($item) {
			return Header::fromAttributes($item);
		}, $values);

		return $this;
    }

    /**
     * Set response body property
     * 
     * @param array $values 
     * @return $this 
     * @throws AssertionException 
     */
    public function setResponseBody(array $values)
    {
        $values = (new PrepareDescriptors)->call($values);
		Assert::assertIsArrayOfArray($values);
		$this->responseBody = array_map(function ($item) {
			return BodyPart::fromAttributes($item);
		}, $values);

		return $this;
    }

    public function getResponseHeaders(): array
    {
        return $this->responseHeaders ?? [];

    }

    public function getResponseBody(): array
    {
        return $this->responseBody ?? [];
    }
}