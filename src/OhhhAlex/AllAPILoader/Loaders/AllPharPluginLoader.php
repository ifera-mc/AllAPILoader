<?php

namespace OhhhAlex\AllAPILoader\Loaders;

use pocketmine\plugin\PharPluginLoader;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginDescription;
use pocketmine\Server;

class AllPharPluginLoader extends PharPluginLoader{
	
	/** @var \ClassLoader */
	private $loader;
	
	/**
	 * AllFolderPluginLoader constructor.
	 *
	 * @param \ClassLoader $loader
	 */
	public function __construct(\ClassLoader $loader){
		$this->loader = $loader;
		parent::__construct($loader);
	}
	
	/**
	 * @param string $file
	 * @return null|PluginDescription
	 * @throws \ReflectionException
	 */
	public function getPluginDescription(string $file): ?PluginDescription{
		$phar = new \Phar($file);
		if(isset($phar["plugin.yml"])){
			$pluginYml = $phar["plugin.yml"];
			if($pluginYml instanceof \PharFileInfo){
				$description = new PluginDescription($pluginYml->getContent());
				if(!Server::getInstance()->getPluginManager()->getPlugin($description->getName()) instanceof Plugin and !in_array(Server::getInstance()->getApiVersion(), $description->getCompatibleApis())){
					$api = (new \ReflectionClass("pocketmine\plugin\PluginDescription"))->getProperty("api");
					$api->setAccessible(true);
					$api->setValue($description, [Server::getInstance()->getApiVersion()]);
					return $description;
				}
			}
		}
		return null;
	}
}
