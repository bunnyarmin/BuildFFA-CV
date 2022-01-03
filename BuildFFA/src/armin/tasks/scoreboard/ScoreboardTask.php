<?php

declare(strict_types=1);

namespace armin\tasks\scoreboard;

use armin\BuildFFA;
use armin\EventListener;
use armin\utils\scoreboard\ScoreboardAPI;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

class ScoreboardTask extends Task
{
    private BuildFFA $plugin;

    public function __construct(BuildFFA $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @todo add stats
     */
    public function onRun(): void
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $onlinePlayer) {
            //$stats = new Config(EventListener::STATS_FILE . "buildffa.json", Config::JSON);

            ScoreboardAPI::remove($onlinePlayer);#
            ScoreboardAPI::create($onlinePlayer, " §l§8» §r§3BuildFFA §l§8«");
            ScoreboardAPI::setLine($onlinePlayer, 0, "§1");
            ScoreboardAPI::setLine($onlinePlayer, 1, " §l§8» §r§7Kills");
            ScoreboardAPI::setLine($onlinePlayer, 2, "  §l§7→ §r§a0");
            ScoreboardAPI::setLine($onlinePlayer, 3, "§2");
            ScoreboardAPI::setLine($onlinePlayer, 4, " §l§8» §r§7Deaths");
            ScoreboardAPI::setLine($onlinePlayer, 5, "  §l§7→ §r§c0");
            ScoreboardAPI::setLine($onlinePlayer, 6, "§3");
            ScoreboardAPI::setLine($onlinePlayer, 7, " §l§8» §r§7Elo");
            ScoreboardAPI::setLine($onlinePlayer, 8, "  §l§7→ §r§e0");
            ScoreboardAPI::setLine($onlinePlayer, 9, "§4");
            ScoreboardAPI::setLine($onlinePlayer, 10, "§ediscord.CloudVace.de");
        }
    }
}