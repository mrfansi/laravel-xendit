<?php

namespace Mrfansi\XenditSdk\Contracts;

interface XenditSdk
{
    /**
     * Sets the for-user-id header for xenPlatform sub-account transactions
     *
     * @param  string  $userId  The sub-account user ID
     */
    public function withUserId(string $userId): self;

    /**
     * Sets the with-split-rule header for routing payments to multiple accounts
     *
     * @param  string  $splitRuleId  The split rule ID to apply
     */
    public function withSplitRule(string $splitRuleId): self;

    /**
     * Sets the idempotency key header for preventing duplicate requests.
     *
     * @param  string  $idempotencyKey  The unique key to prevent processing duplicate requests. Can be your reference_id or any GUID. Must be unique across development and production environments.
     */
    public function withIdempotencyKey(string $idempotencyKey): self;
}
