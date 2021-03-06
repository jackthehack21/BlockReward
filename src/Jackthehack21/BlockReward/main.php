<?php

namespace Jackthehack21\BlockReward;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use Jackthehack21\BlockReward\BlockEvents;


class main extends PluginBase implements Listener
{
    public function onEnable()
    {
        if (!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }

        $this->saveResource("config.yml");
        $this->cfg = new Config($this->getDataFolder()."config.yml", Config::YAML, []);
        if($this->cfg->get('money_addon') == true){
            if ($this->getServer()->getPluginManager()->getPlugin('EconomyAPI') == true) {
                $this->economy = $this->getServer()->getPluginManager()->getPlugin('EconomyAPI');
                if($this->cfg->get('debug')){
                    $this->getLogger()->info('[BlockReward] - Money Addon loaded !');
                }
            } else {
                if($this->cfg->get('debug')){
                    $this->getServer()->getLogger()->warning('[BlockReward] - Money Addon (EconomyAPI) not found !');
                }
            }
        }
        if($this->cfg->get('debug')){
            $this->getLogger()->info('[BlockReward] - Plugin enabled !');
        }
        $this->getServer()->getPluginManager()->registerEvents(new BlockEvents($this), $this);
        
    }

    public function onDisable()
    {
        if($this->cfg->get('debug')){
            $this->getLogger()->info('[BlockReward] - Plugin Disabled !');
        }
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        if ($cmd->getName() == 'blockreward') {
            if(isset($args[0])){
                switch(strtolower($args[0])){
                    case 'disable':
                    case 'off':
                        if($this->cfg->get('enabled') == false){
                            $sender->sendMessage(C::YELLOW.'BlockReward is already disabled');
                            break;
                        }
                        $this->cfg->set('enabled', false);
                        $this->cfg->save(true);
                        $sender->sendMessage(C::GREEN.'BlockReward is now Disabled !');
                        break;
                    case 'enable':
                    case 'on':
                        if($this->cfg->get('enabled') == true){
                            $sender->sendMessage(C::YELLOW.'BlockReward is already enabled');
                            break;
                        }
                        $this->cfg->set('enabled', true);
                        $this->cfg->save(true); //USE TRUE FOR ASYNC
                        $sender->sendMessage(C::GREEN.'BlockReward is now Enabled !');
                        break;
                    case 'credits':
                        $sender->sendMessage(C::GOLD.'==== '.C::RED.'CREDITS'.C::GOLD.' ====');
                        $sender->sendMessage(C::GREEN.'Developers:');
                        $sender->sendMessage(C::RED.'• '.C::GREEN.'Jackthehaxk21');
                        break;
                    case '?':
                    case 'help':
                        $sender->sendMessage(C::GOLD.'==== '.C::AQUA.'HELP'.C::GOLD.' ====');
                        $sender->sendMessage(C::GREEN.'/blockreward help    '.C::BLACK.'- '.C::GOLD.'Show the help page.');
                        $sender->sendMessage(C::GREEN.'/blockreward credits '.C::BLACK.'- '.C::GOLD.'Who made me, find out here.');
                        $sender->sendMessage(C::GREEN.'/blockreward on|off  '.C::BLACK.'- '.C::GOLD.'Toggle the plugin on/off.');
                        
                        break;
                    default:
                        $sender->sendMessage(C::RED.'Unkown Command, try /blockreward help');
                        break;
                }
            } else {
                $sender->sendMessage(C::RED.'Not a valid arg, try /blockreward help');
                return true;
            }
        }
        return true;
    }
   
}
