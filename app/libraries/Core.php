<?php
/*
* App Core Class
* Creates URL & loads core Controller
* URL FORMAT - /controller/method/params
*/
/*
*ابتدا درخواست گت را کاربر میگیرد 
*و آن آدرس را تجزیه کرده 
*و در قالب آرایه ای  سه قسمتی ذخیره میکند
*به این صورت که
* اولی کنترلر دومی متود و سومی پارامتر باشد
*سپس از کلاس کنترل مورد نظر را نمونه سازی میکند و
*بعد از آن با توجه به متود و پارامتری که ذخیره کرده 
*از کلاس نمونه سازی شده متود مورد نظر را لود میکند
*/

class Core
{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();
        if(isset($url[0])){
            // Look In controllers for first value
            if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
                // If Exist Set as Controller
                 $this->currentController = ucwords($url[0]);
                // Unset 0 Index
                unset($url[0]);   
            }
        }
        // Require the Controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Check for second part URL
        if (isset($url[1])) {
            // Check to see if method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                // Unset 1 Index
                unset($url[1]);
            }
        }
        // get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
