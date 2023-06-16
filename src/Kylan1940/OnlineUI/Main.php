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
	
	const CONFIG_VERSION = 3;
	const PREFIX = "prefix";
	
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
	  $prefix = $this->getConfig()->get(self::PREFIX);
	  if ($sender instanceof Player) {
	    if($cmd->getName() == "online"){
	      $this->online($sender);
	  }
	    } else {
	      if($this->getConfig()->getNested('console-execute') == "message"){
	        $sender->sendMessage($prefix.$this->getConfig()->getNested('console.message'));
	      }
	      elseif($this->getConfig()->getNested('console-execute') == "command"){
	        $command = $this->getConfig()->getNested('console.command');
          $this->getServer()->dispatchCommand($sender, $command);
	      }
	    }
		return true;
	}
	
	public function online($sender){
    $form = new SimpleForm(function (Player $sender, $data){
        if($data === null){
            return true;
        }
    });
    $form->setTitle($prefix.$this->getConfig()->getNested('ui.title'));
    $form->setContent($prefix.$this->getConfig()->getNested('ui.content'));
    foreach($this->getServer()->getOnlinePlayers() as $online){
        $form->addButton($online->getName(), -1, "", $online->getName());
    }
    $form->sendToPlayer($sender);
    return $form;
}

}
