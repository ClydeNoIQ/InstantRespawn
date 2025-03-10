<?php

declare(strict_types=1);

namespace blitz;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class InstantRespawn extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $event->setKeepInventory(true); // Keep inventory on death

        // Skip death screen by forcing an instant respawn
        $this->getScheduler()->scheduleDelayedTask(new class($player) extends Task {
            private Player $player;
            public function __construct(Player $player) {
                $this->player = $player;
            }
            public function onRun(): void {
                if ($this->player->isOnline() && !$this->player->isAlive()) {
                    $this->player->respawn();
                }
            }
        }, 1); // Delay by 1 tick to ensure proper respawn
    }
}
