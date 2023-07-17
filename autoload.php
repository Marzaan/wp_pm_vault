<?php

spl_autoload_register(function ($className) {
	$className = str_replace('PM_Vault\\', 'includes\\', $className);
	$classFile = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';

	if (file_exists($classFile)) {
		require_once $classFile;
	}
});

?>