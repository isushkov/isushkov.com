<?php
class Home extends App
{
    public $pageNotFound = false;

    function __construct() {
        $this->getTheme();
        $this->getPNFMessage();
    }

    public function getPNFMessage() {
        if (isset($_SESSION['page_not_found'])) {
            if ($_SESSION['page_not_found'] == 1) {
                $this->pageNotFound = true;
            } else {
                echo 'session_page_not_found status faled';
            }
            unset($_SESSION['page_not_found']);
        }
    }
}
