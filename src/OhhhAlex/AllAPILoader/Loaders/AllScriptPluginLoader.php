<?php

namespace OhhhAlex\AllAPILoader\Loaders;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\ScriptPluginLoader;
use pocketmine\Server;

class AllScriptPluginLoader extends ScriptPluginLoader{
	
	/**
	 * @param string $file
	 * @return null|PluginDescription
	 * @throws \ReflectionException
	 */
	public function getPluginDescription(string $file): ?PluginDescription{
		$content = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$data = [];
		$insideHeader = false;
		foreach($content as $line){
			if(!$insideHeader and strpos($line, "/**") !== false){
				$insideHeader = true;
			}
			if(preg_match("/^[ \t]+\\*[ \t]+@([a-zA-Z]+)([ \t]+(.*))?$/", $line, $matches) > 0){
				$key = $matches[1];
				$content = trim($matches[3] ?? "");
				if($key === "notscript"){
					return null;
				}
				$data[$key] = $content;
			}
			if($insideHeader and strpos($line, "*/") !== false){
				break;
			}
		}
		if($insideHeader){
			$description = new PluginDescription($data);
			if(!Server::getInstance()->getPluginManager()->getPlugin($description->getName()) instanceof Plugin and !in_array(Server::getInstance()->getApiVersion(), $description->getCompatibleApis())){
				$api = (new \ReflectionClass("pocketmine\plugin\PluginDescription"))->getProperty("api");
				$api->setAccessible(true);
				$api->setValue($description, [Server::getInstance()->getApiVersion()]);
				return $description;
			}
		}
		return null;
	}
}
