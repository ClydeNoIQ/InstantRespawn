<?php

declare(strict_types=1);

namespace blitz;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;

class InstantRespawn extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $event->setKeepInventory(true); // Keep inventory (optional)
        $event->setKeepExperience(true); // Keep XP (optional)

        // Skip death screen by forcing immediate respawn
        $this->getScheduler()->scheduleDelayedTask(new class($player) extends \pocketmine\scheduler\Task {
            private Player $player;
            public function __construct(Player $player) {
                $this->player = $player;
            }
            public function onRun(): void {
                if ($this->player->isOnline() && !$this->player->isAlive()) {
                    $this->player->respawn();
                }
            }
        }, 1); // 1-tick delay to ensure smooth respawn
    }
}
