<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class HumanClientMain
{
    public function __construct()
    {
		if (!defined('HUMAN_ROOT')) {
			define('HUMAN_ROOT', dirname(__FILE__));
			require(HUMAN_ROOT . DIRECTORY_SEPARATOR . 'util' . DIRECTORY_SEPARATOR . 'HumanAutoloader.php');
			HumanAutoloader::register();
		}
    }
}

