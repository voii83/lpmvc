<?php


class Router
{
	private $router;

	public function __construct()
	{
		$routesPath = ROOT.'/config/routes.php';
		$this->router = include($routesPath);
	}

	public function run()
	{
		// Получить строку запроса
		$uri = $this->getUri();

		//Проверить наличие такого запроса в routes.php
		foreach ($this->router as $uriPattern => $path) {
			if (preg_match("~^$uriPattern$~", $uri)) {

				//Получаем внутренний путь из внешнего согласно правилу
				$internalRoute = preg_replace("~$uriPattern~", $path, $uri);

				//Определить какой контроллер и экшен обрабатывают запрос
				$segments = explode('/', $internalRoute);
				$controllerName = array_shift($segments).'Controller';
				$controllerName = ucfirst($controllerName);

				$actionName = 'action'.ucfirst(array_shift($segments));

				//Если в качестве парметра передаётся get запрос удаляем его
				$isGet = strpos(implode($segments), "?");
				if ($isGet === false) {
					$parameters = $segments;
				} else {
					$parameters = [];
				}

				//Подключить файл класса-контроллера
				$controllerFile = ROOT.'/controllers/'.$controllerName.'.php';

				if (file_exists($controllerFile)) {
					include_once($controllerFile);
				}

				//Создать объект,вызвать метод (т.е. экшен)
				$controllerObject = new $controllerName;
				$result = call_user_func_array([$controllerObject, $actionName], $parameters);

				if ($result != null) {
					break;
				}
			}
		}






	}

	/**
	 * Returns request string
	 * @return string
	 */
	private function getUri()
	{
		if (!empty($_SERVER['REQUEST_URI'])) {
			return trim($_SERVER['REQUEST_URI'], '/');
		}
	}
}