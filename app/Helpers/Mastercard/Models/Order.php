<?php

namespace App\Helpers\Mastercard\Models;

class Order implements Model
{
    private object $object;

    public function __construct(
        string $reference_id,
        ?string $currency = 'USD',
        ?float $amount = null
    ) {
        $this->object = (object)[
            'id'       => $reference_id,
            'currency' => $currency,
        ];

        if ($amount) $this->object->amount = (string)$amount;
    }

    public function setAmount(float $amount): self
    {
        $this->object->amount = (string)$amount;
        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->object->currency = $currency;
        return $this;
    }

    public function toJson(): array
    {
        if (!isset($this->object->amount)) throw new \Error('amount must be set');
        return (array)$this->object;
    }
};
