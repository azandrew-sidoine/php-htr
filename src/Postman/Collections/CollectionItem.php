<?php

namespace Drewlabs\Htr\Postman\Collections;

use Drewlabs\Htr\Postman\Contracts\ComponentInterface;
use Drewlabs\Htr\Contracts\ComponentInterface as HTRComponentInterface;
use Drewlabs\Htr\Contracts\Descriptor;
use Drewlabs\Htr\Postman\Collections\Request as CollectionsRequest;
use Drewlabs\Htr\Request;
use Drewlabs\Htr\RequestDirectory;
use RuntimeException;

class CollectionItem implements ComponentInterface
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * Creates Collection item instance
     * 
     * @param mixed $name 
     * @param null|CollectionsRequest $request 
     * @param mixed $response 
     * @return void 
     */
    public function __construct(
        private $name = null,
        private ?CollectionsRequest $request = null,
        private $response = null,
        private ?string $description = null
    ) {
    }

    /**
     * Create instance from HTR request component
     * 
     * @param HTRComponentInterface $component 
     * @return static 
     */
    public static function fromRequestComponent(HTRComponentInterface $component): static
    {
        if ($component instanceof RequestDirectory) {
            return static::fromRequestDirectory($component);
        }

        if ($component instanceof Request) {
            return static::fromRequest($component);
        }

        throw new RuntimeException('Unsupported project request component');
    }

    /**
     * Create collection item from HTR request directory
     * 
     * @param RequestDirectory $component 
     * @return static 
     */
    private static function fromRequestDirectory(RequestDirectory $component): static
    {
        $self = new static($component->getName());
        $self = $self->withItems(array_map(function (HTRComponentInterface $current) {
            return static::fromRequestComponent($current);
        }, $component->getItems()));
        return $self;
    }

    /**
     * Create collection item from HTR request object
     * 
     * @param Request $component 
     * @return static 
     */
    private static function fromRequest(Request $component): static
    {
        $params = $component->getParams() ?? [];
        $url = new Url(
            $component->getUrl(), 
            null,
            array_reduce($params, function($carry, Descriptor $param) {
            $carry[$param->getName()] = $param->getValue();
            return $carry;
        }, []));
        // Create postman authorization parameter
        /**
         * @var Authorization
         */
        $auth = null;
        if ($authorization = $component->getAuthorization()) {
            $auth = Authorization::fromHTrAutorization($authorization);
        }
        // Create new postman collection request
        $request = new CollectionsRequest(
            $url,
            $auth,
            $component->getMethod(),
            array_map(function(Descriptor $header) {
                return Header::fromHTRHeader($header);
            },$component->getHeaders()),
            new RequestBody($component->getBody()),
            $component->getName(),
            $component->getDescription()
        );
        $self = new static($component->getName(), $request);
        return $self;
    }

    public function getComponentName(): string
    {
        return 'item';
    }

    public function toArray(): array
    {
        return array_merge(
            [
                'name' => $this->name,
                'description' => $this->description,
                'response' => $this->response ?? []
            ],
            $this->request ? [$this->request->getComponentName() => $this->request->toArray()] : [],
            !empty($this->items) ? ['item' => array_values(array_map(function (ComponentInterface $item) {
                return $item->toArray();
            }, $this->items))] : []
        );
    }

    /**
     * Immutable items property value setter
     * 
     * @param array $items 
     * @return static 
     */
    public function withItems(array $items)
    {
        $self = clone $this;

        $self->items = $items;

        return $self;
    }

    /**
     * Push new item to the end of the stack
     * 
     * @param CollectionItem $item 
     * @return $this 
     */
    public function addItem(CollectionItem $item)
    {
        $this->items[] = $item;

        return $this;
    }
}
