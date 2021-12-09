<?php

declare(strict_types=1);

namespace aiptu\muteall;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use function gettype;
use function rename;

final class MuteAll extends PluginBase implements Listener
{
	private bool $mute = false;

	public function onEnable(): void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		$this->checkConfig();
	}

	public function onPlayerChat(PlayerChatEvent $event): void
	{
		if ($event->isCancelled()) {
			return;
		}

		$player = $event->getPlayer();

		if (($this->mute === true) && !$player->hasPermission('muteall.bypass')) {
			$player->sendMessage(TextFormat::colorize($this->getConfig()->get('message', "&cYou can't chat when global mute is enabled")));
			$event->cancel();
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
	{
		$bool = $this->mute;
		if ($bool === true) {
			$this->mute = false;
		} else {
			$this->mute = true;
		}
		$sender->sendMessage(TextFormat::GREEN . 'Turn ' . ($bool ? 'off' : 'on') . ' mute chat for all players');
		return true;
	}

	private function checkConfig(): void
	{
		$this->saveDefaultConfig();

		if ($this->getConfig()->get('config-version', 1) !== 1) {
			$this->getLogger()->notice('Your configuration file is outdated, updating the config.yml...');
			$this->getLogger()->notice('The old configuration file can be found at config.old.yml');

			rename($this->getDataFolder() . 'config.yml', $this->getDataFolder() . 'config.old.yml');

			$this->reloadConfig();
		}

		foreach ([
			'message' => 'string',
		] as $option => $expectedType) {
			if (($type = gettype($this->getConfig()->getNested($option))) !== $expectedType) {
				throw new \TypeError("Config error: Option ({$option}) must be of type {$expectedType}, {$type} was given");
			}
		}
	}
}
