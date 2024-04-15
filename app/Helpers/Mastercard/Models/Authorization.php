<?php

namespace App\Helpers\Mastercard\Models;

use App\Helpers\Mastercard\Interaction as IntrOp;

final class Authorization implements Model
{
    private object $object;
    private Interaction $interaction;

    public function __construct(string $merchant_id)
    {
        $this->interaction = (new Interaction(IntrOp::AUTHORIZE))
            ->setMerchantId($merchant_id);

        $this->object = (object)[];
    }

    /**
     * Retrieves the active instance of an `Interaction` Model
     *
     * @return Interaction
     */
    public function getInteraction(): Interaction
    {
        return $this->interaction;
    }

    /**
     * Set order model metadata
     *
     * @param Order $order
     */
    public function setOrder(Order $order): self
    {
        $this->object->order = $order->toJson();
        return $this;
    }

    /**
     * Sets the customer model metadata
     *
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): self
    {
        $this->object->customer = $customer->toJson();
        return $this;
    }

    public function toJson(): array
    {
        if (!isset($this->object->order)) throw new \Error("order must be set");
        $this->object->interaction = $this->interaction->toJson();

        return (array)$this->object;
    }
};
