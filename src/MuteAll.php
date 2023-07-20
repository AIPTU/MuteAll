<?php

/*
 * Copyright (c) 2021-2023 AIPTU
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/AIPTU/MuteAll
 */

declare(strict_types=1);

namespace aiptu\muteall;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use function is_string;

class MuteAll extends PluginBase implements Listener {
	private const CONFIG_VERSION = 1.2;

	private bool $muteAll = false;

	private string $message;
	private string $turnOnMessage;
	private string $turnOffMessage;

	public function onEnable() : void {
		$this->checkConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	private function checkConfig() : void {
		$this->saveDefaultConfig();
		$config = $this->getConfig();

		if (!$config->exists('config-version') || $config->get('config-version', self::CONFIG_VERSION) !== self::CONFIG_VERSION) {
			$this->getLogger()->warning('An outdated config was provided; attempting to generate a new one...');

			$oldConfigPath = Path::join($this->getDataFolder(), 'config.old.yml');
			$newConfigPath = Path::join($this->getDataFolder(), 'config.yml');

			$filesystem = new Filesystem();
			try {
				$filesystem->rename($newConfigPath, $oldConfigPath);
			} catch (IOException $e) {
				$this->getLogger()->critical('An error occurred while attempting to generate the new config: ' . $e->getMessage());
				$this->getServer()->getPluginManager()->disablePlugin($this);
				return;
			}

			$this->reloadConfig();
		}

		$this->message = is_string($config->get('message')) ? $config->get('message') : '&cYou can\'t chat when global mute is enabled';
		$this->turnOnMessage = is_string($config->get('turn-on')) ? $config->get('turn-on') : '&aTurn on mute chat for all players';
		$this->turnOffMessage = is_string($config->get('turn-off')) ? $config->get('turn-off') : '&aTurn off mute chat for all players';
	}

	public function isMuteAll() : bool {
		return $this->muteAll;
	}

	public function setMuteAll(bool $value) : void {
		$this->muteAll = $value;
	}

	public function onPlayerChat(PlayerChatEvent $event) : void {
		if ($event->isCancelled()) {
			return;
		}

		$player = $event->getPlayer();

		if ($this->isMuteAll() && !$player->hasPermission('muteall.bypass')) {
			$player->sendMessage(TextFormat::colorize($this->message));
			$event->cancel();
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
		$this->setMuteAll(!$this->isMuteAll());
		$sender->sendMessage(TextFormat::colorize($this->isMuteAll() ? $this->turnOnMessage : $this->turnOffMessage));
		return true;
	}
}
