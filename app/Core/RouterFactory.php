<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		// Vytvoření routeru
		$router = new RouteList;

		// Přidání směrování pro UserPresenter
		$router->addRoute('user/<id>', 'User:default');  // Detail uživatele podle ID
		$router->addRoute('user/', 'User:default');      // Seznam uživatelů
		
		return $router;
	}
}
