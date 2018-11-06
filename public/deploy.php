<?php
///**
// * @access protected
// * @author Judzhin Miles <info[woof-woof]msbios.com>
// */
//use Zend\Mvc\Application;
//use Zend\ServiceManager\Exception\ServiceNotCreatedException;
//
///**
// * This makes our life easier when dealing with paths. Everything is relative
// * to the application root now.
// */
//chdir(dirname(__DIR__));
//
//// Decline static file requests back to the PHP built-in webserver
//if (php_sapi_name() === 'cli-server') {
//    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
//    if (__FILE__ !== $path && is_file($path)) {
//        return false;
//    }
//    unset($path);
//}
//
//// Composer autoloading
//include __DIR__ . '/../vendor/autoload.php';
//
//if (!class_exists(Application::class)) {
//    throw new RuntimeException(
//        "Unable to load application.\n"
//        . "- Type `composer install` if you are developing locally.\n"
//        . "- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
//        . "- Type `docker-compose run zf composer install` if you are using Docker.\n"
//    );
//}
//
//try {
//// Run the application!
//    Application::init([
//        'modules' => [
//            'Zend\Router',
//            'Zend\Log',
//            'MSBios\Monolog',
//            'MSBios\Deploy',
//        ],
//        'module_listener_options' => [
//            'module_paths' => [
//                // './module',
//                './vendor',
//            ],
//            'config_glob_paths' => [
//                realpath(__DIR__) . '/../config/autoload/{{,*.}global,{,*.}local}.php',
//            ],
//            'config_cache_enabled' => false,
//            'config_cache_key' => 'application.config.cache',
//            'module_map_cache_enabled' => false,
//            'module_map_cache_key' => 'application.module.cache',
//            'cache_dir' => 'data/cache/',
//        ],
//    ])->run();
//} catch (Exception $exception) {
//    http_response_code(404);
//    die('Hey, Watson, where the hell are you ?! Iron and carry an ax, here the person is looking for the page, go to the admin - we will find.');
//}
