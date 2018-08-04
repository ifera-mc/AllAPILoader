<?php

namespace OhhhAlex\AllAPILoader;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLoadOrder;
use OhhhAlex\AllAPILoader\Loaders\AllFolderPluginLoader;
use OhhhAlex\AllAPILoader\Loaders\AllPharPluginLoader;
use OhhhAlex\AllAPILoader\Loaders\AllScriptPluginLoader;

class Main extends PluginBase{
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerInterface(new AllPharPluginLoader($this->getServer()->getLoader()));
		$this->getServer()->getPluginManager()->registerInterface(new AllScriptPluginLoader());
		if($this->getServer()->getPluginManager()->getPlugin("DevTools") instanceof Plugin or $this->getServer()->getPluginManager()->getPlugin("FolderPluginLoader") instanceof Plugin){
			$this->getServer()->getPluginManager()->registerInterface(new AllFolderPluginLoader($this->getServer()->getLoader()));
		}
		$this->getServer()->getPluginManager()->loadPlugins($this->getServer()->getPluginPath(), [AllPharPluginLoader::class, AllScriptPluginLoader::class, AllFolderPluginLoader::class]);
		$this->getServer()->enablePlugins(PluginLoadOrder::STARTUP);
	}
}
