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
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use function rename;

final class MuteAll extends PluginBase
{
	use SingletonTrait;

	private const CONFIG_VERSION = 1.1;

	private bool $muteAll = false;

	public function onEnable(): void
	{
		self::setInstance($this);

		$this->checkConfig();

		$this->getServer()->getPluginManager()->registerEvents(new EventHandler(), $this);
	}

	public function isMuteAll(): bool
	{
		return $this->muteAll;
	}

	public function setMuteAll(bool $value): void
	{
		$this->muteAll = $value;
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
	{
		if ($this->isMuteAll()) {
			$this->setMuteAll(false);
		} else {
			$this->setMuteAll(true);
		}
		$config = $this->getConfig();
		$sender->sendMessage(TextFormat::colorize($this->isMuteAll() ? $config->get('turn-on', '&aTurn on mute chat for all players') : $config->get('turn-off', '&aTurn off mute chat for all players')));
		return true;
	}

	private function checkConfig(): void
	{
		$this->saveDefaultConfig();

		if (!$this->getConfig()->exists('config-version') || ($this->getConfig()->get('config-version', self::CONFIG_VERSION) !== self::CONFIG_VERSION)) {
			$this->getLogger()->warning('An outdated config was provided attempting to generate a new one...');
			if (!rename($this->getDataFolder() . 'config.yml', $this->getDataFolder() . 'config.old.yml')) {
				$this->getLogger()->critical('An unknown error occurred while attempting to generate the new config');
				$this->getServer()->getPluginManager()->disablePlugin($this);
			}
			$this->reloadConfig();
		}
	}
}
