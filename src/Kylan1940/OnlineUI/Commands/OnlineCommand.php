<?php

namespace Kylan1940\OnlineUI\Commands;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\utils\Config;
//use pocketmine\event\Listener;
use Kylan1940\OnlineUI\Main;
use Kylan1940\OnlineUI\Form\{Form, SimpleForm};

class OnlineCommand extends Command implements PluginOwned {
	
	const PREFIX = "prefix";
	private $plugin;
	
	public function __construct(Main $plugin){
	  $this->plugin = $plugin;
    parent::__construct($plugin->getConfig()->getNested('command.command'), $plugin->getConfig()->getNested('command.description'), $plugin->getConfig()->getNested('command.usage'), $plugin->getConfig()->getNested('command.aliases'));
    $this->setPermissions(['onlineui.command']);
    $this->setPermissionMessage($plugin->getConfig()->getNested('no-permission'));
    $plugin->getResource("config.yml");
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args){
    $prefix = $this->plugin->getConfig()->get(self::PREFIX);
    if($sender instanceof Player){
      if($sender->hasPermission("onlineui.command")){
        $this->online($sender);
      } else {
        $sender->sendMessage($prefix.$this->plugin->getConfig()->getNested('no-permission'));
      }
    }
    if(!$sender instanceof Player){
      if($this->plugin->getConfig()->getNested('console-execute') == "message"){
        $sender->sendMessage($prefix.$this->plugin->getConfig()->getNested('console.message'));
      } 
      if($this->plugin->getConfig()->getNested('console-execute') == "command"){
        $command = $this->plugin->getConfig()->getNested('console.command');
        $this->plugin->getServer()->dispatchCommand($sender, $command);
      }
    }
  }
  
  public function online($sender){
    $form = new SimpleForm(function (Player $sender, $data){
      if($data === null){
        return true;
      }
    });
    $form->setTitle($this->plugin->getConfig()->getNested('ui.title'));
    $form->setContent($this->plugin->getConfig()->getNested('ui.content'));
    foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
      $form->addButton($online->getName(), -1, "", $online->getName());
    }
    $form->sendToPlayer($sender);
    return $form;
  }
	
  public function getOwningPlugin():Plugin{
    return $this->plugin;
	}

}
