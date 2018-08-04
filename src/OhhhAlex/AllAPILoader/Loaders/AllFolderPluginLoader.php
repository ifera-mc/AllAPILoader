<?php

namespace OhhhAlex\AllAPILoader\Loaders;

use FolderPluginLoader\FolderPluginLoader;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginDescription;
use pocketmine\Server;

class AllFolderPluginLoader extends FolderPluginLoader {
	
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
        if (is_dir($file) and file_exists($file . "/plugin.yml")) {
            $yaml = @file_get_contents($file . "/plugin.yml");
            if ($yaml != "") {
                $description = new PluginDescription($yaml);
                if (!Server::getInstance()->getPluginManager()->getPlugin($description->getName()) instanceof Plugin and !in_array(Server::getInstance()->getApiVersion(), $description->getCompatibleApis())) {
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
