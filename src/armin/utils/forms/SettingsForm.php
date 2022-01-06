<?php

declare(strict_types=1);

namespace armin\utils\forms;

use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;

class SettingsForm
{
    public function createSettingsForm(): MenuForm
    {
        return new MenuForm(
            "§l§8» §r§eSettings §l§8«",
            "",
            [
                new MenuOption("§l§8→ §r§6Kits"),
                new MenuOption("§l§8→ §r§dInventory Sort"),
                new MenuOption("§r§cExit")
            ],
            function (Player $submitter, int $selected): void {
                switch ($selected) {
                    case 0:
                        $form = new KitsForm();
                        $submitter->sendForm($form);
                        break;
                    case 1:
                        break;
                    case 2:
                        break;
                    default:
                        break;
                }
            }
        );
    }
}