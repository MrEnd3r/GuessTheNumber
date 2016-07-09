<?php
namespace SalmonDE;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\FizzSound;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat as TF;
use SalmonDE\NumberTask;

class Number extends PluginBase implements Listener{

	protected $winner;

	public function onEnable(){
	    @mkdir($this->getDataFolder());
		$dir = $this->getDataFolder();
		if(file_exists($dir.'currentgame.json')){
			unlink($dir.'currentgame.json');
			$this->getLogger()->debug('Temp file Deleted!');
		}
	    $this->saveResource('config.yml');
		$lang = $this->getConfig()->get("Language");
		$this->saveResource($lang.".php");
		include($this->getDataFolder().$lang.".php");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		$lang = $this->getConfig()->get("Language");
		require($this->getDataFolder().$lang.".php");
		$replace = array(
		    '{min}',
			'{max}',
			'{number}',
	        '{qnum}',
			'{numq}',
			'{name}',
			'{count}',
			'{itemname}'
		);
		$replaced = array(
		    '$min',
			'$max',
			'$num',
	        '$qnum',
			'$numq',
			'$name',
			'$data[2]',
			'$itemname'
		);
		$dir = $this->getDataFolder();
		$information = json_decode(file_get_contents($dir.'currentgame.json'), true);
		if($cmd == 'guessgamesolution' || $cmd == 'Guessgamesolution'){
            if($sender->hasPermission('guessthenumber.solution')){
				if(file_exists($dir.'currentgame.json')){
				    if($information[behavior] == 5){
					    $sender->sendMessage(TF::BLUE.$normalsolution.(string) $information[num]);
				    }elseif($information[behavior] == 1350){
					    $sender->sendMessage(TF::BLUE.$squaresolution.(string) $information[numq]);
				    }
				}else{
					$sender->sendMessage(TF::RED.'No Game Active!');
				}
			}
		}elseif($cmd == 'guessgameabort' || $cmd == 'Guessgameabort'){
				if($sender->hasPermission('guessthenumber.abort')){
					if(file_exists($dir.'currentgame.json')){
					    unlink($dir.'currentgame.json');
				        $this->getServer()->broadcastMessage(TF::RED.TF::BOLD.$gameaborted);
						return true;
					}else{
						$sender->sendMessage(TF::GOLD.$nogameactive);
						return true;
					}
				}else{
					$sender->sendMessage(TF::GOLD.$nopermission);
					return true;
				}
		}elseif(file_exists($dir.'currentgame.json')){
			$sender->sendMessage(TF::RED.$gamealreadyactive);
		}else{
		    $tempfile = fopen($dir.'currentgame.json','w');
		    if($cmd == 'guessgame' || $cmd == 'Guessgame'){
				$min = $this->getConfig()->get('Minimum');
				$max = $this->getConfig()->get('Maximum');
		        $status = 1;
		        $behavior = 5;
		        $num = mt_rand($min,$max);
		        $store = array(
		            'status' => "$status",
					'num' => "$num",
					'behavior' => "$behavior"
		        );
		        fwrite($tempfile, json_encode($store));
				$firstlinec = str_ireplace($replace, $replaced, $firstline);
				$this->getServer()->broadcastMessage(TF::GOLD.TF::BOLD."\n");
				$this->getServer()->broadcastMessage(TF::GOLD.TF::BOLD.$header);
				$this->getServer()->broadcastMessage(TF::AQUA.TF::BOLD.$firstlinec);
				$this->getServer()->broadcastMessage(TF::AQUA.TF::BOLD.$secondline);
				$this->getServer()->broadcastMessage(TF::AQUA.TF::BOLD.$thirdline);
				$this->getServer()->broadcastMessage(TF::AQUA.TF::BOLD.$fourthline);
				$this->getServer()->broadcastMessage(TF::GOLD.TF::BOLD.$bottom);
				$this->getServer()->broadcastMessage(TF::GOLD.TF::BOLD."\n");
				$this->getServer()->broadcastMessage(TF::RED.$advice);
		        return true;
		    }elseif($cmd == 'guessgamesquare' || $cmd == 'Guessgamesquare'){
		        $status = 1;
		        $behavior = 1350;
		        $qnum = mt_rand(1,20);
		        $numq = $qnum * $qnum;
				$store = array(
		            'status' => "$status",
					'qnum' => "$qnum",
					'numq' => "$numq",
					'behavior' => "$behavior"
		        );
				fwrite($tempfile, json_encode($store));
				$this->getServer()->broadcastMessage(TF::GOLD.TF::BOLD."\n");
				$this->getServer()->broadcastMessage(TF::GOLD.TF::BOLD.$qheader);
				$this->getServer()->broadcastMessage(TF::AQUA.TF::BOLD.$qfirstline);
				$this->getServer()->broadcastMessage(TF::AQUA.TF::BOLD.$qsecondline);
				$this->getServer()->broadcastMessage(TF::AQUA.TF::BOLD.$qthirdline);
				$this->getServer()->broadcastMessage(TF::AQUA.TF::BOLD.$qfourthline);
				$this->getServer()->broadcastMessage(TF::GOLD.TF::BOLD.$qbottom);
				$this->getServer()->broadcastMessage(TF::GOLD.TF::BOLD."\n");
				$this->getServer()->broadcastMessage(TF::RED.$advice);
		    }
		}
	}

	public function onChat(PlayerChatEvent $event){
		$dir = $this->getDataFolder();
		$min = $this->getConfig()->get('Minimum');
		$max = $this->getConfig()->get('Maximum');
		if(file_exists($dir.'currentgame.json')){
			$lang = $this->getConfig()->get("Language");
		    include($this->getDataFolder().$lang.".php");
            $information = json_decode(file_get_contents($dir.'currentgame.json'), true);
		    if($information[status] == 1){
			    $player = $event->getPlayer();
			    $message = $event->getMessage();
			    if(is_numeric($message)){
					$player->sendMessage(TF::LIGHT_PURPLE.'In 5 Sekunden erfährst du, ob es richtig ist!');
					$task = new NumberTask($this, $player, $message);
					$this->getServer()->getScheduler()->scheduleDelayedTask($task, 100);
					$event->setCancelled();
			    }
			}
		}
    }

	public function onJoin(PlayerJoinEvent $event){
		$dir = $this->getDataFolder();
		if(file_exists($dir.'currentgame.json')){
			$lang = $this->getConfig()->get("Language");
		    include($this->getDataFolder().$lang.".php");
            $information = json_decode(file_get_contents($dir.'currentgame.json'), true);
			$player = $event->getPlayer();
			if($information[behavior] == 5){
				$player->sendMessage(TF::GOLD.TF::BOLD."\n");
				$player->sendMessage(TF::GOLD.TF::BOLD.$header);
				$player->sendMessage(TF::AQUA.TF::BOLD.$firstline);
				$player->sendMessage(TF::AQUA.TF::BOLD.$secondline);
				$player->sendMessage(TF::AQUA.TF::BOLD.$thirdline);
				$player->sendMessage(TF::AQUA.TF::BOLD.$fourthline);
				$player->sendMessage(TF::GOLD.TF::BOLD.$bottom);
                $player->sendMessage(TF::GOLD.TF::BOLD."\n");
				$player->sendMessage(TF::RED.$advice);
			}elseif($information[behavior] == 1350){
				$player->sendMessage(TF::GOLD.TF::BOLD."\n");
				$player->sendMessage(TF::GOLD.TF::BOLD.$qheader);
				$player->sendMessage(TF::AQUA.TF::BOLD.$qfirstline);
				$player->sendMessage(TF::AQUA.TF::BOLD.$qsecondline);
				$player->sendMessage(TF::AQUA.TF::BOLD.$qthirdline);
				$player->sendMessage(TF::AQUA.TF::BOLD.$qfourthline);
				$player->sendMessage(TF::GOLD.TF::BOLD.$qbottom);
                $player->sendMessage(TF::GOLD.TF::BOLD."\n");
				$player->sendMessage(TF::RED.$advice);
			}
		}
	}

	public function givePrize($winner){
		$lang = $this->getConfig()->get("Language");
		include($this->getDataFolder().$lang.".php");
		$dir = $this->getDataFolder();
        $information = json_decode(file_get_contents($dir.'currentgame.json'), true);
		$name = $winner->getDisplayName();
		if($information[behavior] == 5){
			foreach($this->getServer()->getOnlinePlayers() as $players){
				$players->getLevel()->addSound(new FizzSound($players->getPosition()));
			}
			unlink($dir.'currentgame.json');
			$this->getServer()->broadcastMessage(TF::GREEN.TF::BOLD.$congratulation);
			$this->getServer()->broadcastMessage(TF::GOLD.TF::BOLD.$rightnumber);
			$item = $this->getConfig()->get('Item');
			$data = explode(':', $item);
			$itemname = Item::get($data[0])->getName();
			$winner->getInventory()->addItem(new Item($data[0], $data[1], $data[2]));
		    $winner->sendMessage(TF::GREEN.TF::BOLD.$winnermessage);
		}elseif($behavior == 1350){
			foreach($this->getServer()->getOnlinePlayers() as $players){
				$players->getLevel()->addSound(new FizzSound($players->getPosition()));
			}
			unlink($dir.'currentgame.json');
			$this->getServer()->broadcastMessage(TF::GREEN.TF::BOLD.$qcongratulation);
			$this->getServer()->broadcastMessage(TF::GOLD.TF::BOLD.$qrightnumber);
			$item = $this->getConfig()->get('SquareItem');
			$data = explode(':', "$item");
			$itemname = Item::get($data[0])->getName();
			$winner->getInventory()->addItem(new Item($data[0], $data[1], $data[2]));
		    $winner->sendMessage(TF::LIGHT_PURPLE.TF::BOLD.$qwinnermessage);
		}
	}

	public function onDisable(){
		$dir = $this->getDataFolder();
		if(file_exists($dir.'currentgame.json')){
			unlink($dir.'currentgame.json');
			$this->getLogger()->debug('Temp file Deleted!');
		}
	}
}