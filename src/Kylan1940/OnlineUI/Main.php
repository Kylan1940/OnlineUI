<?php

namespace Kylan1940\OnlineUI;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Kylan1940\OnlineUI\Commands\OnlineCommand;
use Kylan1940\OnlineUI\UpdateNotifier\ConfigUpdater;

class Main extends PluginBase {
	
	public function onEnable() : void {
    ConfigUpdater::update($this);
    $this->getResource("config.yml");
    $this->getServer()->getCommandMap()->register('Online', new OnlineCommand($this));
  }
  
}
