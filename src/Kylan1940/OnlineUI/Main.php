<?php

namespace Kylan1940\OnlineUI;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use Kylan1940\OnlineUI\Form\{Form, SimpleForm};

class Main extends PluginBase implements Listener{
	
	private $targetPlayer = [];
	
	public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->getResource("config.yml");
        
        // Check config
        if($this->getConfig()->get("config-ver") != "2")
        {
            $this->getLogger()->info("OnlineUI's config is NOT up to date. Please delete the config.yml and restart the server or the plugin may not work properly.");
        }
  }
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
	  if ($sender instanceof Player) {
	    if($cmd->getName() == "online"){
	      $this->online($sender);
	  }
	    } else {
	      $sender->sendMessage($this->getConfig()->get("only-ingame"));
	    }
		return true;
	}
	
	public function online($sender){
    $form = new SimpleForm(function (Player $sender, $data){
        if($data === null){
            return true;
        }
        $target = $data;
        $this->targetPlayer[$sender->getName()] = $target;
    });
    $form->setTitle($this->getConfig()->get("title"));
    $form->setContent($this->getConfig()->get("content"));
    foreach($this->getServer()->getOnlinePlayers() as $online){
        $form->addButton($online->getName(), -1, "", $online->getName());
    }
    $form->sendToPlayer($sender);
    return $form;
}

}
