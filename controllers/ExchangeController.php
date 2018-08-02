<?php

class ExchangeController
{
	private $filename;
	private $mode;

	public function actionIndex()
	{
		session_start();
		if (isset($_GET['mode'])) {
			$this->mode = $_GET['mode'];
		}

		if (isset($_GET['filename'])) {
			$this->filename = $_GET['filename'];
		}

		switch ($this->mode) {
			case 'checkauth' :
				if($_SERVER['PHP_AUTH_USER'] == "user" && $_SERVER['PHP_AUTH_PW'] == "123") {
					echo "success\n";
					echo session_name() . "\n";
					echo session_id() . "\n";
					exit;
				}
				else {
					echo "failure\n";
					exit;
				}

			case 'init' :
				$zip = extension_loaded('zip') ? 'yes' : 'no';
				echo 'zip='.$zip."\n";
				echo "file_limit=0\n";
				exit;

			case 'file' :
				// вытаскиваем сырые данные
				$data = file_get_contents('php://input');

				// Сохраняем файл импорта в zip архиве
				file_put_contents($this->filename, $data);

				// распаковываем
				if(file_exists($this->filename)) {
					// работаем с zip
					$zip = new ZipArchive;
					//все в порядке с архивом?
					if($res = $zip->open($this->filename, ZIPARCHIVE::CREATE)) {

						// распаковываем файлы в формате xml куда-то
						// в нашем случае в этот же каталог
						$zip->extractTo(ROOT."/upload/catalog1c/");
						$zip->close();

						// удаляем временный файл
						unlink($this->filename);
						//Всё получилось?
						echo "success\n";
						exit;
					}
				}
				// если ничего не получилось
				echo "failure\n";
				exit;

			case 'import' :
				echo "success\n";
				exit;

			case 'complete' :
				echo "success\n";
				exit;
		}


		return true;
	}

	public function actionCheckauth()
	{

	}

	public function actionInit()
	{

	}

	public function actionFile()
	{

	}

	public function actionImport()
	{

	}

	public function actionComplete() {

	}




}