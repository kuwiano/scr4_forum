<?php
namespace Controllers;

use \BusinessLogic\AuthenticationManager;
use BusinessLogic\UserManager;
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
           $password = $this->getParam(self::PARAM_PASSWORD);

           $errors = array();
           // is password long enough
           if(strlen($password) < 6) {
               $errors[] = "Password needs to be atleast 6 characters long.";
           }

           // is unique username
           if(UserManager::isUsernameTaken($username)) {
               $errors[] = "Username already taken!";
           }

           if(sizeof($errors) > 0) {
               return $this->renderView('login', array(
                   'username' => '',
                   'errors' => $errors
               ));
           }

           // add and login
           $userId = UserManager::addUser($username, $password);
           if($userId == 0) {
               $errors[] = "Couldn't save user in DB.";
               // error case
               $this->renderView('login', array(
                   'username' => $this->getParam(self::PARAM_USER_NAME),
                   'errors' => $errors
               ));
           }

           AuthenticationManager::authenticate($username, $password);
           return $this->redirect('Index', 'Home');
       }
    }

    public function POST_LogOut() {
        AuthenticationManager::signOut();
        return $this->redirect('Index', 'Home');
    }
}