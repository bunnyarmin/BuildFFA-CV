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
    public function create(Player $player, string $title): void
    {
        $packet = SetDisplayObjectivePacket::create("sidebar", $player->getName(), " {$title} ", "dummy", 0);
        $player->getNetworkSession()->sendDataPacket($packet);
    }

    public function remove(Player $player): void
    {
        $packet = RemoveObjectivePacket::create($player->getName());
        $player->getNetworkSession()->sendDataPacket($packet);
    }

    public function setLine(Player $player, int $line, string $text): void
    {
        $entry = new ScorePacketEntry();
        $entry->scoreboardId = $line;
        $entry->objectiveName = $player->getName();
        $entry->score = $line;
        $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entry->customName = " {$text}  ";

        $packet = SetScorePacket::create(SetScorePacket::TYPE_CHANGE, [$entry]);
        $player->getNetworkSession()->sendDataPacket($packet);
    }

    public function removeLine(Player $player, int $line): void
    {
        $entry = new ScorePacketEntry();
        $entry->scoreboardId = $line;
        $entry->objectiveName = $player->getName();
        $entry->score = $line;
        $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;

        $packet = SetScorePacket::create(SetScorePacket::TYPE_REMOVE, [$entry]);
        $player->getNetworkSession()->sendDataPacket($packet);
    }
}