<?php

declare(strict_types=1);

namespace armin;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class EventListener implements Listener
{
    private $plugin;

    public function __construct(BuildFFA $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        if (!file_exists(BuildFFA::PLAYER_FILE . $player->getName() . ".json")) {
            $pdata = new Config(BuildFFA::PLAYER_FILE . $player->getName() . ".json", Config::JSON);
            $pdata->setNested("inventory.sandstone", 0);
            $pdata->setNested("inventory.gold-sword", 1);
            $pdata->setNested("inventory.stone-pickaxe", 2);
            $pdata->setNested("inventory.stick", 3);
            $pdata->setNested("inventory.bow", 4);
            $pdata->setNested("inventory.specialitem", 8);
            $pdata->setNested("kits.enderman", false);
            $pdata->save();

            $stats = new Config(BuildFFA::STATS_FILE, Config::JSON);
            $stats->setNested($player->getName() . ".kills", 0);
            $stats->setNested($player->getName() . ".deaths", 0);
            $stats->setNested($player->getName() . ".elo", 0);
            $stats->save();
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        $player = $event->getPlayer();

        $event->setJoinMessage("");

        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
        $player->getHungerManager()->setEnabled(false);
        $player->getXpManager()->setXpLevel(0);

        $player->getInventory()->setItem(0, ItemFactory::getInstance()->get(ItemIds::GLASS_PANE)->setCustomName(""));
        $player->getInventory()->setItem(1, ItemFactory::getInstance()->get(ItemIds::GLASS_PANE)->setCustomName(""));
        $player->getInventory()->setItem(2, ItemFactory::getInstance()->get(ItemIds::GLASS_PANE)->setCustomName(""));
        $player->getInventory()->setItem(3, ItemFactory::getInstance()->get(ItemIds::GLASS_PANE)->setCustomName(""));
        $player->getInventory()->setItem(4, ItemFactory::getInstance()->get(ItemIds::ENDER_CHEST)->setCustomName("§r§eSettings"));
        $player->getInventory()->setItem(5, ItemFactory::getInstance()->get(ItemIds::GLASS_PANE)->setCustomName(""));
        $player->getInventory()->setItem(6, ItemFactory::getInstance()->get(ItemIds::GLASS_PANE)->setCustomName(""));
        $player->getInventory()->setItem(7, ItemFactory::getInstance()->get(ItemIds::GLASS_PANE)->setCustomName(""));
        $player->getInventory()->setItem(8, ItemFactory::getInstance()->get(ItemIds::GLASS_PANE)->setCustomName(""));

        $spawn = $this->plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();
        $player->teleport(new Position($spawn->getX(), $spawn->getY(), $spawn->getZ(), $spawn->getWorld()));

        foreach ($this->plugin->getServer()->getOnlinePlayers() as $onlinePlayer) {
            $onlinePlayer->sendActionBarMessage("§r§8[ §a+ §8]§7 " . $player->getName());
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();

        $event->setQuitMessage("");

        foreach ($this->plugin->getServer()->getOnlinePlayers() as $onlinePlayer) {
            $onlinePlayer->sendActionBarMessage("§r§8[ §c- §8]§7 " . $player->getName());
        }
    }

    public function onPlayerMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();

        $pdata = new Config(BuildFFA::PLAYER_FILE . $player->getName() . ".json", Config::JSON);

        if ($player->getPosition()->getY() >= 100) {
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();

            $player->getInventory()->setItem($pdata->getNested("inventory.sandstone"), ItemFactory::getInstance()->get(ItemIds::SANDSTONE)->setCustomName("§r§eSandstone")->setCount(64));
            $player->getInventory()->setItem($pdata->getNested("inventory.gold-sword"), ItemFactory::getInstance()->get(ItemIds::GOLD_SWORD)->setCustomName("§r§eGoldschwert"));
            $player->getInventory()->setItem($pdata->getNested("inventory.stone-pickaxe"), ItemFactory::getInstance()->get(ItemIds::STONE_PICKAXE)->setCustomName("§r§eSandstone"));
            $player->getInventory()->setItem($pdata->getNested("inventory.stick"), ItemFactory::getInstance()->get(ItemIds::STICK)->setCustomName("§r§eStick"));
            $player->getInventory()->setItem($pdata->getNested("inventory.bow"), ItemFactory::getInstance()->get(ItemIds::BOW)->setCustomName("§r§eBogen"));
            $player->getInventory()->setItem(9, ItemFactory::getInstance()->get(ItemIds::ARROW)->setCustomName("§r§ePfeile")->setCount(8));

            $player->getArmorInventory()->setHelmet(ItemFactory::getInstance()->get(ItemIds::CHAIN_HELMET)->setCustomName("§r§eHelm"));
            $player->getArmorInventory()->setChestplate(ItemFactory::getInstance()->get(ItemIds::IRON_CHESTPLATE)->setCustomName("§r§eBrustkorb"));
            $player->getArmorInventory()->setLeggings(ItemFactory::getInstance()->get(ItemIds::CHAIN_LEGGINGS)->setCustomName("§r§eHose"));
            $player->getArmorInventory()->setBoots(ItemFactory::getInstance()->get(ItemIds::CHAIN_BOOTS)->setCustomName("§r§eSchuhe"));
        }
    }

    /**
     * public function onEntityDamage(EntityDamageEvent $event): void
     * {
     * $player = $event->getEntity();
     *
     * $stats = new Config(BuildFFA::STATS_FILE, Config::JSON);
     *
     * if ($player instanceof Player) {
     * if ($event instanceof EntityDamageByEntityEvent) {
     * $killer = $event->getDamager();
     * if ($killer instanceof Player) {
     * if ($player->getHealth() < $event->getBaseDamage()) {
     * $scoreboard = new Scoreboard();
     *
     * //Player
     * $pdeaths = $stats->getNested($player->getName() . ".deaths");
     * $pelo = $stats->getNested($player->getName() . ".elo");
     *
     * $stats->setNested($player->getName() . ".deaths", $pdeaths + 1);
     * $stats->setNested($player->getName() . ".elo", $pelo - mt_rand(5, 25));
     *
     * $scoreboard->updateDeaths($player);
     *
     * //Killer
     * $kkills = $stats->getNested($killer->getName() . ".kills");
     * $kelo = $stats->getNested($killer->getName() . ".elo");
     *
     * $stats->setNested($killer->getName() . ".kills", $kkills + 1);
     * $stats->setNested($killer->getName() . ".elo", $kelo + mt_rand(5, 25));
     *
     * $scoreboard->updateKills($killer);
     *
     * $spawn = $this->plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();
     * $player->teleport(new Position($spawn->getX(), $spawn->getY(), $spawn->getZ(), $spawn->getWorld()));
     * }
     * }
     * }
     * }
     *
     * if ($event->getCause() !== EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
     * $event->cancel();
     * }elseif ($event->getCause() === EntityDamageEvent::CAUSE_VOID){
     * if ($player->getLastDamageCause() !== EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
     * $spawn = $this->plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();
     * $player->teleport(new Position($spawn->getX(), $spawn->getY(), $spawn->getZ(), $spawn->getWorld()));
     * $event->cancel();
     * }else{
     * //killer???
     * $player->sendMessage("TODO");
     * }
     * }
     * }
     */

    public function onPlayerDeath(PlayerDeathEvent $event): void
    {
        $event->setDeathMessage("");
        $event->setDrops([]);
    }

    public function onPlayerInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getPlayer()->getInventory()->getItemInHand();

        if ($item->getId() === ItemIds::ENDER_CHEST) {
            $player->sendMessage("§csoon lol");
        }
    }

    public function onItemDrop(PlayerDropItemEvent $event): void
    {
        $event->cancel();
    }

    public function onBlockPlace(BlockPlaceEvent $event): void
    {
        $block = $event->getBlock();

        if ($block->getId() === ItemIds::SANDSTONE) {
            $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(
                function () use ($block) {
                    $x = $block->getPosition()->getX();
                    $y = $block->getPosition()->getY();
                    $z = $block->getPosition()->getZ();

                    $block->getPosition()->getWorld()->setBlock(new Vector3($x, $y, $z), BlockFactory::getInstance()->get(BlockLegacyIds::AIR, 0), false);
                }), 100);
        }
    }

    public function onInventoryTransaction(InventoryTransactionEvent $event): void
    {
        $event->cancel();
    }
}