<?php
namespace Controllers;

use \BusinessLogic\AuthenticationManager;
use DataLayer\DataLayerFactory;

class User extends Controller {
    const PARAM_USER_NAME = 'un';
    const PARAM_PASSWORD = 'pwd';

    public function GET_LogIn() {
        if(AuthenticationManager::isAuthenticated()) {
            $this->redirect('Index', 'Home');
        }

        return $this->renderView('login', array(
            // optional, if not set empty
            'user' => AuthenticationManager::getAuthenticatedUser(),
            'username' => $this->getParam(self::PARAM_USER_NAME)
        ));
    }

    public function POST_Submit() {
       $action = $this->getParam("type");

       if($action == "login") {
           if(!AuthenticationManager::authenticate(
               $this->getParam(self::PARAM_USER_NAME),
               $this->getParam(self::PARAM_PASSWORD))) {

               return $this->renderView('login', array(
                   'username' => $this->getParam(self::PARAM_USER_NAME),
                   'errors' => array('Invalid user name or password.')
               ));
           }
           return $this->redirect('Index', 'Home');
       } else {
           // register
           $username = $this->getParam(self::PARAM_USER_NAME);

           // is unique username
           $userRepository = DataLayerFactory::getUserDataLayer();
           if($userRepository->isUsernameTaken($username)) {
               return $this->renderView('login', array(
                   'errors' => array('Username already taken!')
               ));
           }

           // add and login
           $password = $this->getParam(self::PARAM_PASSWORD);
           $userRepository->addUser($username, $password);
           return $this->redirect('Index', 'Home');
       }
    }

    public function POST_LogOut() {
        AuthenticationManager::signOut();
        return $this->redirect('Index', 'Home');
    }
}