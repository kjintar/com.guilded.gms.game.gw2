<?php

namespace gms\system\game\provider;
use gms\data\game\GameServer;
use wcf\util\JSON;

/**
 * Implementation of GameProvider for Guild Wars 2
 *
 * @author	Niklas Siepmann
 * @copyright	2014
 * @license	Creative Commons 3.0 <BY-NC-SA> <http://creativecommons.org/licenses/by-nc-sa/3.0/deed>
 * @package	com.guilded.gms.game.gw2
 * @subpackage	system.game.provider
 * @category	Guilded 2.0
 */
 
 class Gw2GameProvider extends AbstractGameProvider implements IGameProvider {
	/**
	 * @see	\wcf\system\game\provider\AbstractGameProvider::$baseUrl
	 */
	protected $baseUrl  = 'https://api.guildwars2.com/v1/';

	/**
	 * @see	\wcf\system\game\provider\IGameProvider::getGuild()
	 */
	public function getGuild($server, $name) {
		$guild = $this->getData(array(
			'guild_details.json'
		), array(
			'guild_name' => $name
		));

		return array(
			'name' => $guild['guild_name'],
			'id' => $guild['guild_id'],
			'tag' => $guild['tag'],
			'emblem' => $guild['emblem']
		);
	}
	
	/**
	 * @see	\wcf\system\game\provider\IGameProvider::getServer()
	 */
	public function getServer($name) {
		// API not released yet
		return null;
	}
	
	/**
	 * @see	\wcf\system\game\provider\IGameProvider::getCharacter()
	 */
	public function getCharacter($server, $name) {
		// API not released yet
		return null;
	}
	
	/**
	 * @see	\wcf\system\game\provider\IGameProvider::getItem()
	 */
	public function getItem($itemID) {
		// Set language
		switch(WCF::getLanguage()->languageCode) {			
			case 'en':
			case 'de':
			case 'fr':
			case 'es': 
				$language = WCF::getLanguage()->languageCode;
				break;
			default: 
				$language = 'en';
				break;
		}
	
		$item = $this->getData(array (
			'item_details.json'
		), array(
			'item_id' => $itemID,
			'lang' => $language
		));

		$result = array (
			'id' => $item['item_id'],
			'name' => $item['name'],
			'description' => $item['description'],
			'type' => $item['type'],
			'level' => $item['level'],
			'rarity' => $item['rarity'],
			'iconID' => $item['icon_file_id'],
			'iconSignature' => $item['icon_file_signature'],
			'flags' => $item['flags'],
			'restrictions' => $item['restrictions']
		);
		
		
		// Add type based data
		switch($item['type']) {
			case 'Weapon': 
				$result['weapon'] = $item['weapon'];
				break;
			case 'Armor':
				$result['armor'] = $item['armor'];
				break;
			case 'Bag': 
				$result['bag'] = $item['bag'];
				break;
			case 'Consumable': 
				$result['consumable'] = $item['consumable'];
				break;
			case 'Container': 
				$result['container'] = $item['container'];
				break;
			case 'Gizmo': 
				$result['gizmo'] = $item['gizmo'];
				break;
			case 'Trinket': 
				$result['trinket'] = $item['trinket']; 
				break;
			case 'UpgradeComponent': 
				$result['upgradeComponent'] = $item['upgrade_component'];
				break;
		}
		
		return $result;
	}

	/**
	 * Sending request and returns response data.
	 */
	protected function sendRequest($url) {
		parent::sendRequest($url);

		$this->data = JSON::decode($this->data, true);
	}
}
