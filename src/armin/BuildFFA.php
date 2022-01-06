<?php

declare(strict_types=1);

namespace armin;

use pocketmine\plugin\PluginBase;

class BuildFFA extends PluginBase
{
    const PLAYER_FILE = "/home/armin/database/player/";
    const STATS_FILE = "/home/armin/database/stats/buildffa.json";

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
}