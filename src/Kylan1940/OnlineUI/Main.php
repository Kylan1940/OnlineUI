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
	
	const CONFIG_VERSION = 2;
	
	public function onEnable() : void {
	      $this->updateConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->getResource("config.yml");
  }
  
  private function updateConfig(){
        if (!file_exists($this->getDataFolder() . 'config.yml')) {
            $this->saveResource('config.yml');
            return;
        }
        if ($this->getConfig()->get('config-version') !== self::CONFIG_VERSION) {
            $config_version = $this->getConfig()->get('config-version');
            $this->getLogger()->info("Your Config isn't the latest. We renamed your old config to §bconfig-" . $config_version . ".yml §6and created a new config");
            rename($this->getDataFolder() . 'config.yml', $this->getDataFolder() . 'config-' . $config_version . '.yml');
            $this->saveResource('config.yml');
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
