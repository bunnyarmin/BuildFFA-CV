<?php

declare(strict_types=1);

namespace armin\utils\scoreboard;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;

class ScoreboardAPI
{
    public static function create(Player $player, string $title): void
    {
        //keiner macht es so easy :D
        $player->getNetworkSession()->sendDataPacket(SetDisplayObjectivePacket::create("sidebar", "{$player->getName()}", " {$title} ", "dummy", 0));
    }

    public static function remove(Player $player): void
    {
        $player->getNetworkSession()->sendDataPacket(RemoveObjectivePacket::create("{$player->getName()}"));
    }

    public static function setLine(Player $player, int $line, string $text): void
    {
        $entry = new ScorePacketEntry();
        $entry->scoreboardId = $line;
        $entry->objectiveName = "{$player->getName()}";
        $entry->score = $line;
        $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entry->customName = " $text  ";

        $player->getNetworkSession()->sendDataPacket(SetScorePacket::create(SetScorePacket::TYPE_CHANGE, [$entry]));
    }

    public static function removeLine(Player $player, int $line): void
    {
        $entry = new ScorePacketEntry();
        $entry->scoreboardId = $line;
        $entry->objectiveName = "{$player->getName()}";
        $entry->score = $line;
        $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;

        $player->getNetworkSession()->sendDataPacket(SetScorePacket::create(SetScorePacket::TYPE_REMOVE, [$entry]));
    }
}