<?php

namespace Mrfansi\LaravelXendit\Traits;

use InvalidArgumentException;

trait HasDispatchActions
{

    /**
     * Dispatch action based on command argument
     *
     * @throws InvalidArgumentException When action is invalid
     */
    private function dispatchAction(string $action): void
    {
        if (!in_array($action, $this->actions)) {
            throw new InvalidArgumentException(
                'Invalid action. Use: ' . implode(', ', $this->actions)
            );
        }

        $this->{$action}();
    }
}
