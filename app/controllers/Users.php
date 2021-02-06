<?php
class Users extends Controller {
     private $userModel;
    public function __construct(){
        $this->userModel = $this->model("User");
    }
    public function register(){

        //check for request
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            //sanitize inputs
            $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

            $data = [
                "name" => trim($_POST['name'] ), "email" =>trim($_POST['email'] ),
                "password" =>trim($_POST['password'] ), "confirm_password" =>trim($_POST['confirm_password'] ),
                "name_err" =>"", "email_err" =>"", "password_err" =>"", "confirm_password_err" =>""
            ];
            //validate name
            if(empty($data['name'])){
                $data['name_err'] = "name can't be empty";
            }

            if(empty($data['password'])){
                $data['password_err'] = "password can't be empty";
            }elseif (strlen($data['password']) < 6){
                $data['password_err'] = "password can't be less than 6 chars";
            }

            if(empty($data['email'])){
                $data['email_err'] = "email can't be empty";
            }elseif($this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = "email already exists";
            }

            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = "confirm password can't be empty";
            }elseif ($data['confirm_password'] != $data['password']){
                $data['confirm_password_err'] = "confirm password isn't the same";
            }
            //make sure errors are empty

            if(empty($data['name_err']) && empty($data['password_err']) && empty($data['email_err']) && empty($data['confirm_password_err'])){
                //form validated
                $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);
                //add user ----register
                if($this->userModel->register($data)){
                    //redirect
                    redirect("users/login");
                }else{
                    die("something went wrong");
                }
            }
            else{
                $this->view("users/register",$data);
            }
        }else{
            // view opened not through the request
            //data passed to the view
            $data = [
               "name" =>"", "email" =>"", "password" =>"", "confirm_password" =>"",
                "name_err" =>"", "email_err" =>"", "password_err" =>"", "confirm_password_err" =>""
            ];
            //load the view

            $this->view("users/register",$data);
        }
    }
    public function login(){

        //check for request
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

            $data = [
                "email" =>trim($_POST['email'] ), "password" =>trim($_POST['password'] ),
                "email_err" =>"", "password_err" =>""
            ];
            //validate password
            if(empty($data['password'])){
                $data['password_err'] = "password can't be empty";
            }elseif (strlen($data['password']) < 6){
                $data['password_err'] = "password can't be less than 6 chars";
            }
            if(empty($data['email'])){
                $data['email_err'] = "email can't be empty";
            }
            //check if email exists
            if(!$this->userModel->findUserByEmail($data['email'])){

                //didn't find the email in the database

                $data['email_err'] = "email doesn't exist";
            }

            //make sure errors are empty
            if(empty($data['password_err']) && empty($data['email_err'])){
                //check if password correct
                $loggedInUser = $this->userModel->login($data['email'],$data['password']);
                if($loggedInUser){
                    //he is logged ----create session
                    $this->createUserSession($loggedInUser);
                }else{
                    $data['password_err'] = "password isn't correct";
                    $this->view("users/login",$data);
                }
            }
            else{
                $this->view("users/login",$data);
            }
        }else{
            //data passed to the view
            $data = [
                "email" =>"", "password" =>"",
                "email_err" =>"", "password_err" =>""
            ];
            //load the view
            $this->view("users/login",$data);
        }
    }
    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->username;
        $_SESSION['user_email'] = $user->email;
        redirect("posts");
    }
    public function logout(){
        session_unset();
        session_destroy();
        redirect("users/login");
    }
}