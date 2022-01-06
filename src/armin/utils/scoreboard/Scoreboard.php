<?php

declare(strict_types=1);

namespace armin\utils\scoreboard;

use armin\BuildFFA;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class Scoreboard
{
    public function sendScoreboard(Player $player): void
    {
        $stats = new Config(BuildFFA::STATS_FILE, Config::JSON);

        $kills = $stats->getNested($player->getName() . ".kills");
        $deaths = $stats->getNested($player->getName() . ".deaths");
        $elo = $stats->getNested($player->getName() . ".elo");

        $scoreboard = new ScoreboardAPI();
        $scoreboard->create($player, " §l§8» §r§3BuildFFA §l§8« ");
        $scoreboard->setLine($player, 0, "§1");
        $scoreboard->setLine($player, 1, " §l§8» §r§7Kills");
        $scoreboard->setLine($player, 2, "  §l§7→ §r§a{$kills}");
        $scoreboard->setLine($player, 3, "§2");
        $scoreboard->setLine($player, 4, " §l§8» §r§7Deaths");
        $scoreboard->setLine($player, 5, "  §l§7→ §r§c{$deaths}");
        $scoreboard->setLine($player, 6, "§1");
        $scoreboard->setLine($player, 7, " §l§8» §r§7Elo");
        $scoreboard->setLine($player, 8, "  §l§7→ §r§a{$elo}");
        $scoreboard->setLine($player, 9, "§2");
        $scoreboard->setLine($player, 10, "§eCloudVace.de");
    }

    public function updateKills(Player $player): void
    {
        $stats = new Config(BuildFFA::STATS_FILE, Config::JSON);

        $kills = $stats->getNested($player->getName() . ".kills");

        $scoreboard = new ScoreboardAPI();
        $scoreboard->removeLine($player, 2);
        $scoreboard->setLine($player, 2, "  §l§7→ §r§a{$kills}");
    }

    public function updateDeaths(Player $player): void
    {
        $stats = new Config(BuildFFA::STATS_FILE, Config::JSON);

        $deaths = $stats->getNested($player->getName() . ".deaths");

        $scoreboard = new ScoreboardAPI();
        $scoreboard->removeLine($player, 5);
        $scoreboard->setLine($player, 5, "  §l§7→ §r§a{$deaths}");
    }

    public function updateElo(Player $player): void
    {
        $stats = new Config(BuildFFA::STATS_FILE, Config::JSON);

        $elo = $stats->getNested($player->getName() . ".elo");

        $scoreboard = new ScoreboardAPI();
        $scoreboard->removeLine($player, 8);
        $scoreboard->setLine($player, 8, "  §l§7→ §r§a{$elo}");
    }
}