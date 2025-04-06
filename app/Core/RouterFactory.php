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
		$router->addRoute('user/<id>', 'User:detail');  // Detail uživatele podle ID
		$router->addRoute('user', 'User:default');      // Seznam uživatelů
		$router->addRoute('newuser', 'User:new');      // Seznam uživatelů
		// Přidání směrování pro ClientPresenter
		$router->addRoute('client/<id>', 'Client:detail');  // Detail klienta podle ID
		$router->addRoute('client', 'Client:default');      // Seznam klientů

		// Přidání směrování pro CsePresenter
		$router->addRoute('case/<id>', 'Case:detail');  // Detail případu podle ID
		$router->addRoute('case', 'Case:default');      // Seznam opřípadů

		$router->addRoute('', 'Home:default');  // Homepage
		
		// Přidání směrování pro SignPresenter
		$router->addRoute('login', 'Sign:in');  //login
		$router->addRoute('logout', 'Sign:out');      //logout

		// Přidání směrování pro Errory
		//$router->addRoute('404', 'Error:4xx');  // Homepage
		$router->addRoute('error/<presenter>/<action>', [
            'module' => 'Error',
            'presenter' => 'Error4xx',
            'action' => '404',
        ]);

      
		return $router;
	}
}
