<?php

class Cart
{
    private static $instance;

    protected $cart = [];
    protected $cartString;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function __construct()
    {
        $this->cartString = $_COOKIE['cart'];
        $this->deserialize();
    }

    private function __clone() {}
    private function __wakeup() {}

    protected function deserialize()
    {
        $this->cart = json_decode($this->cartString);

        return $this;
    }

    protected function serialize()
    {
        $this->cartString = json_encode($this->cart);

        setcookie('cart', $this->cartString, time() + (86400 * 30), "/");

        return $this;
    }

    public function insert($id, $quantity, $price, $name, $attributes = [])
    {
        $this->deserialize();

        if (!array_key_exists($id, $this->cart)) {
            $this->cart[$id] = [
                'quantity' => $quantity,
                'price' => $price,
                'name' => $name,
                'attributes' => $attributes
            ];

            $this->serialize();

            return true;
        }

        return false;
    }

    public function remove($id)
    {
        $this->deserialize();

        if (!array_key_exists($id, $this->cart)) {
            unset($id, $this->cart);

            $this->serialize();

            return true;
        }

        return false;
    }

    public function update($id, $quantity, $price, $name, $attributes = [])
    {
        $this->deserialize();

        if (array_key_exists($id, $this->cart)) {
            $this->cart[$id] = [
                'quantity' => $quantity,
                'price' => $price,
                'name' => $name,
                'attributes' => $attributes
            ];

            $this->serialize();

            return true;
        }

        return false;
    }

    public function clear()
    {
        $this->deserialize();

        unset($this->cart);
        $this->cart = [];

        $this->serialize();

        return $this;
    }

    public function count()
    {
        $this->deserialize();

        return count($this->cart);
    }
}
