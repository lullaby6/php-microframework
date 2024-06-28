<?php

class AsyncFunction extends Thread {
    private $callback;
    private $arg;

    public function __construct($callback, $arg) {
        $this->callback = $callback;
        $this->arg = $arg;
    }

    public function run() {
        if (is_callable($this->callback)) {
            $callback($this->arg);
        }
    }
}

function async_function($callback, $arg) {
    if (!is_callable($callback)) {
        throw new InvalidArgumentException('The first argument must be a valid callback.');
    }

    $async_function = new AsyncFunction($callback, $arg);

    if (!$async_function->start()) {
        throw new RuntimeException('Failed to start the thread.');
    }

    return $async_function;
}
