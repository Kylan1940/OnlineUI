<?php

namespace Kylan1940\OnlineUI;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use Kylan1940\OnlineUI\Form\{Form, SimpleForm};

class Main extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onCommand(CommandSender $p, Command $cmd, string $label, array $data): bool{
		switch($cmd->getName()){
		case "online":
			if($p instanceof Player){
				$this->online($p);
				return true;
			}
		}
		return true;
	}
	public function online($p){
		$form = new SimpleForm(function (Player $sender, int $data = null){
            $result = $data;
            if ($result === null) {
                return true;
            }
			$this->targetPlayer[$p->getName()] = $target;
		});
		$form->setTitle($this->getConfig()->get("title"));
		$form->setContent($this->getConfig()->get("content"));
		foreach($this->getServer()->getOnlinePlayers() as $online){
			$form->addButton($online->getName(), -1, "", $online->getName(), 0, "textures/ui/confirm");
		}
		$form->sendToPlayer($p);
		return $form;
	}
}