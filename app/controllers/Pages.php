<?php
class Pages extends Controller {

    public function  __construct(){

    }
    public function index(){
        if (isLogged()){
            redirect("posts");
        }
        $data = [
            "title"=>"welcome",
            "description" => "this is description of the page"
        ];
        $this->view("pages/index",$data);
    }
    public function about(){
        $data = [
            "title"=>"about us"
        ];
        $this->view("pages/about",$data);
    }
}