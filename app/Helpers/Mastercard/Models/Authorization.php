<?php

namespace App\Helpers\Mastercard\Models;

use App\Helpers\Mastercard\Interaction;

class Authorization implements Model
{
    private object $object;

    public function __construct(string $merchant_id)
    {
        $this->object = (object)[
            "interaction" => [
                "operation" => Interaction::AUTHORIZE,
                "merchant" => ["name" => $merchant_id]
            ],
        ];
    }

    public function setOrder(Order $order): self
    {
        $this->object->order = $order->toJson();
        return $this;
    }

    public function toJson(): array
    {
        if (!isset($this->object->order)) throw new \Error("order must be set");
        return (array)$this->object;
    }
};
