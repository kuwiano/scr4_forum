<?php

namespace BusinessLogic;

class ContextManager {
    private static $context = null;

    private function __construct() { }

    public static function getInstance() {
        if(self::$context == null) {
            self::init_context();
        }
        return self::$context;
    }

    private static function init_context() {
        // add current user
        self::$context = new \Domain\Context(
            AuthenticationManager::getAuthenticatedUser(),
            PostManager::getLatestPost(),
           isset($_REQUEST['c']) ? $_REQUEST['c'] : null
        );
    }
}