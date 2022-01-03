<?php

declare(strict_types=1);

namespace armin;

use armin\tasks\scoreboard\ScoreboardTask;
use pocketmine\plugin\PluginBase;

class BuildFFA extends PluginBase
{
    /**
     * @todo alles :D
     */

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        //change to 10 when using vserver because pc spacking bei localen host :D
        $this->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($this), 20);
    }
}