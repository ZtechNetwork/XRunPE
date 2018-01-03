<?php
namespace ZtechNetwork;

use pocketmine\plugin\PluginBase;
use pocketmine\pligin\Plugin;
use pocketmine\scheduler\PluginTask;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\entity\Entity;
use pocketmine\entity\Effect;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\EntityDamageEvent;
use pocketmine\event\player\BlockBreakEvent;
use pocketmine\event\player\BlockPlaceEvent;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\block\Block;
use pocketmine\tile\Sign;
use pocketmine\item\Item;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\level\Position;

class XRunPE extends PluginBase implements Listener {
	
	const NAME = "XRun PE";
	const CODENAME = "[BETA]";
	const VERSION = "1.0.0.0";
	const XRUN_API = "1.0.0";
	
		public $prefix = C::GRAY . "[" . C::WHITE . C::BOLD . "S" . C::RED . "G" . C::RESET . C::GRAY . "] ";
		public $mode = 0;
		public $maps = array();
		public $currentLevel = "";
	
		public function onEnable()
		{
			$this->getServer()->getPluginManager()->registerEvents($this ,$this);
			$this->getLogger()->info(C::GREEN . "XRunPE has successfully loaded!");
			$this->saveResource("config.yml");
			@mkdir($this->getDataFolder());
			$config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
			if($config->get("maps")!=null)
			{
				$this->maps = $config->get("maps");
			}
			foreach($this->maps as $lev)
			{
				$this->getServer()->loadLevel($lev);
			}
			if($config->get("firework_effect")==null){
			$config->set("firework_effect","ON");
			}
			$config->save();
			$this->getServer()->getScheduler()->scheduleRepeatingTask(new GameSender($this), 20);
			$this->getServer()->getScheduler()->scheduleRepeatingTask(new RefreshSigns($this), 10);
		}
}