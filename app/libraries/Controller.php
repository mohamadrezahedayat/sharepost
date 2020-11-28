<?php 
/* 
* Base Controller
* loads the models and ciews
*/
/* کلاس والد همه کنترل ها است 
*که شامل متود مدل برای نمونه سازی 
*از مدل ها و متود ویو برای نمونه سازی از ویوها است
*/
class Controller{
    // Load model
    public function model($model){
        // require model file
        require_once '../app/models/'.$model.'.php';

        // instantiate model
        return new $model();
    }
    // load view
    public function view($view, $data=[]){
        //check for the view file
        if(file_exists('../app/views/'.$view.'.php')){
            require_once '../app/views/'.$view.'.php';
        }else{
            // view does not exist
            die('view does not exsist');
        }
    }
}


?>