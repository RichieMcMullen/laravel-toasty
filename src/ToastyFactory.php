<?php

namespace Atomcoder\Toasty;

class ToastyFactory
{
    public function __construct(
        protected ToastManager $manager,
    ) {
    }

    public function for(mixed $target = null): PendingToasty
    {
        return new PendingToasty($this->manager, $target);
    }

    public function __call(string $method, array $arguments): mixed
    {
        return $this->for()->{$method}(...$arguments);
    }
}
