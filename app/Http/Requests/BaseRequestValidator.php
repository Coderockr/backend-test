<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Symfony\Component\Translation\Exception\InvalidResourceException;

abstract class BaseRequestValidator extends Request
{
    /**
     * @var Container
     */
    protected Container $app;

    /**
     * @var \Illuminate\Validation\Validator
     */
    private \Illuminate\Validation\Validator $validator;

    /**
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param $content
     */
    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * @return array
     */
    abstract protected function validationRules(): array;

    /**
     * @return bool|\Exception
     */
    public function validate(): bool|\Exception
    {
        $this->setValidator();

        if ($this->validator->fails()) {
            throw new InvalidResourceException($this->validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return !$this->validator->fails();
    }

    /**
     * @return MessageBag
     */
    public function erros(): MessageBag
    {
        return $this->validator->errors();
    }

    /**
     * @return void
     */
    private function setValidator(): void
    {
        $this->validator = Validator::make(
            $this->all(),
            $this->validationRules(),
            ['create_date.timestamp' => 'create_date is invalid timestamp format']
        );
    }

    /**
     * @param $app
     * @return void
     */
    public function setApp($app): void
    {
        $this->app = $app;
    }

    /**
     * @return void
     */
    public function extendValidator()
    {
        $this->app['validator']->extend('timestamp', function ($attribute, $value, $parameters) {
            try {
                \Carbon\Carbon::createFromTimestamp($value);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        });
    }
}
