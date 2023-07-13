<?php

namespace Kylan1940\OnlineUI;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Kylan1940\OnlineUI\Commands\OnlineCommand;
use Kylan1940\OnlineUI\UpdateNotifier\{ConfigUpdater, PluginUpdater};

class Main extends PluginBase {
	
	public function onEnable() : void {
	  ConfigUpdater::update($this);
    $this->getResource("config.yml");
    $this->getServer()->getCommandMap()->register('Online', new OnlineCommand($this));
  }
  
  /**
	 * Submits an async task which then checks if a new version for the plugin is available.
	 * If an update is available then it would print a message on the console.
	 *
	 * @param string $pluginName
	 * @param string $pluginVersion
	 */
	public static function checkUpdate(string $pluginName, string $pluginVersion): void {
		Server::getInstance()->getAsyncPool()->submitTask(new PluginUpdater($pluginName, $pluginVersion));
	}
	
}
