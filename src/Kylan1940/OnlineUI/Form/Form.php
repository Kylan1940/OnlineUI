<?php

declare(strict_types = 1);

namespace Kylan1940\OnlineUI\Form;

use pocketmine\form\Form as IForm;
use pocketmine\player\Player;

abstract class Form implements IForm{

    /** @var array */
    protected array $data = [];
    /** @var callable|null */
    private $callable;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        $this->callable = $callable;
    }

    /**
     * @deprecated
     * @see Player::sendForm()
     *
     * @param Player $sender
     */
    public function sendToPlayer(Player $sender) : void {
        $sender->sendForm($this);
    }

    public function getCallable() : ?callable {
        return $this->callable;
    }

    public function setCallable(?callable $callable) {
        $this->callable = $callable;
    }

    public function handleResponse(Player $sender, $data) : void {
        $this->processData($data);
        $callable = $this->getCallable();
        if($callable !== null) {
            $callable($sender, $data);
        }
    }

    public function processData(mixed &$data) : void {
    }

    public function jsonSerialize():mixed{
        return $this->data;
    }
}