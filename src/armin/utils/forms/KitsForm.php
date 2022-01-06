<?php

declare(strict_types=1);

namespace armin\utils\forms;

use armin\BuildFFA;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class KitsForm
{
    public function createKitsForm(): MenuForm
    {
        return new MenuForm(
            "§l§8» §r§6Kits §l§8«",
            "",
            [
                new MenuOption("§l§8→ §r§dEnderman"),
                new MenuOption("§r§cExit")
            ],
            function (Player $submitter, int $selected): void {
                switch ($selected) {
                    case 0:
                        $pdata = new Config(BuildFFA::PLAYER_FILE . $submitter->getName() . ".json", Config::JSON);

                        if ($pdata->getNested("kits.available.enderman") === true) {
                            $pdata->set();
                        }
                        break;
                    case 1:
                        break;
                    default:
                        break;
                }
            }
        );
    }
}