<?php
/**
 * This file is part of the Nella Project (https://monolog-tracy.nella.io).
 *
 * Copyright (c) Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information,
 * please view the file LICENSE.md that was distributed with this source code.
 */

namespace Nella\MonologTracy\Tracy;

use Tracy\BlueScreen;
use Tracy\Debugger;

class BlueScreenFactory
{

	/** @var string[] */
	private $info = [];

	/** @var callable[] */
	private $panels = [];

	public function __construct()
	{
		$this->registerInfo('PHP ' . PHP_VERSION);
		if (isset($_SERVER['SERVER_SOFTWARE'])) {
			$this->registerInfo($_SERVER['SERVER_SOFTWARE']);
		}
		$this->registerInfo('Tracy ' . Debugger::VERSION);
	}

	/**
	 * @param string $text
	 */
	public function registerInfo($text)
	{
		if (in_array($text, $this->info, TRUE)) {
			return;
		}

		$this->info[] = $text;
	}

	/**
	 * @param callable $callback
	 */
	public function registerPanel($callback)
	{
		if (in_array($callback, $this->panels, TRUE)) {
			return;
		}
		if (!is_callable($callback, TRUE)) {
			throw new \Nella\MonologTracy\Tracy\PanelIsNotCallableException();
		}

		$this->panels[] = $callback;
	}

	/**
	 * @return BlueScreen
	 */
	public function create()
	{
		$blueScreen = new BlueScreen();
		$blueScreen->info = $this->info;
		foreach ($this->panels as $panel) {
			$blueScreen->addPanel($panel);
		}

		return $blueScreen;
	}

}
