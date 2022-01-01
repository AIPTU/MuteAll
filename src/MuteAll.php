<?php

/*
 *
 * Copyright (c) 2021 AIPTU
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

declare(strict_types=1);

namespace aiptu\muteall;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use function rename;

final class MuteAll extends PluginBase implements Listener
{
	private const CONFIG_VERSION = 1.0;

	private static MuteAll $instance;

	private ConfigProperty $configProperty;

	private bool $mute = false;

	public static function getInstance(): MuteAll
	{
		return self::$instance;
	}

	public function onEnable(): void
	{
		self::$instance = $this;

		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		$this->checkConfig();
	}

	public function isMuteAll(): bool
	{
		return $this->mute;
	}

	public function setMuteAll(bool $value): void
	{
		$this->mute = $value;
	}

	public function onPlayerChat(PlayerChatEvent $event): void
	{
		if ($event->isCancelled()) {
			return;
		}

		$player = $event->getPlayer();

		if ($this->isMuteAll() && !$player->hasPermission('muteall.bypass')) {
			$player->sendMessage(TextFormat::colorize($this->getConfigProperty()->getPropertyString('message', "&cYou can't chat when global mute is enabled")));
			$event->cancel();
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
	{
		if ($this->isMuteAll()) {
			$this->setMuteAll(false);
		} else {
			$this->setMuteAll(true);
		}
		$sender->sendMessage(TextFormat::GREEN . 'Turn ' . ($this->isMuteAll() ? 'on' : 'off') . ' mute chat for all players');
		return true;
	}

	public function getConfigProperty(): ConfigProperty
	{
		return $this->configProperty;
	}

	private function checkConfig(): void
	{
		$this->saveDefaultConfig();

		if (!$this->getConfig()->exists('config-version') || ($this->getConfig()->get('config-version', self::CONFIG_VERSION) !== self::CONFIG_VERSION)) {
			$this->getLogger()->notice('Your configuration file is outdated, updating the config.yml...');
			$this->getLogger()->notice('The old configuration file can be found at config.old.yml');

			rename($this->getDataFolder() . 'config.yml', $this->getDataFolder() . 'config.old.yml');

			$this->reloadConfig();
		}

		$this->configProperty = new ConfigProperty($this->getConfig());
	}
}
