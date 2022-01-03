<?php

declare(strict_types=1);

namespace armin;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\utils\Config;

class EventListener implements Listener
{
    /**
     * bisschen pfuschen
     */
    const PLAYER_FILE = "/home/armin/database/player/";
    const STATS_FILE = "/home/armin/database/stats/";

    private BuildFFA $plugin;

    public function __construct(BuildFFA $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();

        if (!file_exists(self::PLAYER_FILE . $player->getName() . ".json")) {
            $pdata = new Config(self::PLAYER_FILE . $player->getName() . ".json", Config::JSON);
            $pdata->setAll([
                "sandstone" => 1,
                "gold-sword" => 2,
                "stick" => 3,
                "bow" => 4,
                "ender-pearl" => 5
            ]);
            $pdata->save();

            //switch to set nested??? or array????
            $stats = new Config(self::STATS_FILE . "buildffa.json", Config::JSON);
            $stats->set($player->getName() . ".kills", 0);
            $stats->set($player->getName() . ".deaths", 0);
            $stats->set($player->getName() . ".elo", 0);
            $stats->save();
        }
    }

    public function onJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();
        $pdata = new Config(self::PLAYER_FILE . $player->getName() . ".json");

        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->setHealth(20);

        $player->getInventory()->setItem($pdata->get("sandstone"), ItemFactory::getInstance()->get(ItemIds::SANDSTONE)->setCount(64)->setCustomName("§r§eMinecraft-Block"));
        $player->getInventory()->setItem($pdata->get("gold-sword"), ItemFactory::getInstance()->get(ItemIds::GOLD_SWORD)->setCustomName("§r§eArmins hartes Ding :D"));
        $player->getInventory()->setItem($pdata->get("stick"), ItemFactory::getInstance()->get(ItemIds::STICK)->setCustomName("§r§eLümmel von Stefan"));
        $player->getInventory()->setItem($pdata->get("bow"), ItemFactory::getInstance()->get(ItemIds::BOW)->setCustomName("§r§eSpritz-Maschiene"));

        /**
         * ???????????????????????????ßß
         */
        //$player->getInventory()->setItem($pdata->get("ender-pearl"), ItemFactory::getInstance()->getItem(ItemIds::ENDER_PEARL));

        /**
         * @todo add roles
         */
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $onlinePlayer) {
            $onlinePlayer->sendActionBarMessage("§r§8[ §a+ §8]§7 " . $player->getName());
        }
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();

        /**
         * @todo add roles
         */
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $onlinePlayer) {
            $onlinePlayer->sendActionBarMessage("§r§8[ §c- §8]§7 " . $player->getName());
        }
    }
}