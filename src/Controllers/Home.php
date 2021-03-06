<?php
namespace Controllers;

use \BusinessLogic\AuthenticationManager;
use BusinessLogic\DiscussionManager;
use DataLayer\DataLayerFactory;

class Home extends Controller {
    const PARAM_PAGE = 'pnr';

    const ITEMS_PER_PAGE = 10;
    const SHOWN_ADJACENT_PAGES = 5;

    public function GET_Index() {
        $currentPage = 1;
        if($this->hasParam(self::PARAM_PAGE)) {
            $currentPage = $this->getParam(self::PARAM_PAGE);
        }

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        return $this->renderView('home', array(
           'user' => AuthenticationManager::getAuthenticatedUser(),
           'discussions' => DiscussionManager::getDiscussions(),
           'currentPage' => $currentPage,
           'paginationArray' => DiscussionManager::getPaginationArray(self::ITEMS_PER_PAGE, $currentPage, self::SHOWN_ADJACENT_PAGES)
        ));
    }
}