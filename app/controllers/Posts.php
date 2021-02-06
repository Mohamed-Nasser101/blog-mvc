<?php
class Posts extends Controller {

    private $postModel ;
    public function __construct(){
        if (!isLogged()){
            redirect("users/login");
        }
        $this->postModel = $this->model("Post");
    }

    public function index(){
        //get posts
        $posts = $this->postModel->getPosts();
        $data = [
            "posts" =>$posts
        ];
        $this->view("posts/index",$data);
    }
    public function add(){
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            //if the add request submitted
            //sanitize post
            $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
            $data = [
                "title" =>trim($_POST['title']),
                "body" =>trim($_POST['body']),
                "id" =>$_SESSION['user_id'],
                "title_err" =>"",
                "body_err" => ""
            ];
            //validate form
            if (empty($data['title'])){
                $data['title_err'] = "please enter a title";
            }
            if (empty($data['body'])){
                $data['body_err'] = "please enter a text";
            }
            if(empty($data['title_err']) && empty($data['body_err'])){
                if ($this->postModel->addPost($data['title'],$data['body'],$data['id'])){
                   redirect("posts");
                }else{
                    die("something went wrong");
                }
            }else{
                //load data with errors
                $this->view("posts/add",$data);
            }

        }else{    //if page opened without request
        $data = [
            "title" =>"",
            "body" =>""
        ];
        $this->view("posts/add",$data);
        }
    }
    public function show($id){
        $post = $this->postModel->getPostById($id);
        $data = [
            "post" =>$post
        ];
        $this->view("posts/show",$data);
    }
    public function edit($id){
        //if edit request is submitted
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            //sanitize request
            $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

            $data = [
                "title" =>trim($_POST['title']),
                "body" =>trim($_POST['body']),
                "id" =>$id,
                "title_err" =>"",
                "body_err" => ""
            ];
            //validate form
            if (empty($data['title'])){
                $data['title_err'] = "please enter a title";
            }
            if (empty($data['body'])){
                $data['body_err'] = "please enter a text";
            }
            if(empty($data['title_err']) && empty($data['body_err'])){
                if ($this->postModel->editPost($data['id'],$data['title'],$data['body'])){
                    redirect("posts");
                }else{
                    die("something went wrong");
                }
            }else{
                //load data with errors
                $this->view("posts/edit/",$data);
            }

           /* $postId = $id;
            $newTitle = trim($_POST['title']);
            $newBody = trim($_POST['body']);
            if( $this->postModel->editPost($postId,$newTitle,$newBody)){
                redirect("posts/index");
            }else{
                die("something went wrong");
            }*/

        } else {  //if want to open edit page
        $post = $this->postModel->getPostById($id);
        //check if it's the same user
            if ($post->user_id != $_SESSION['user_id']){
                redirect("posts");
                exit();
            }
        $data = [
            "post" =>$post
        ];
        $this->view("posts/edit",$data);
        }
    }
    public function delete($id){
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
          if( $this->postModel->deletePost($id)){
              redirect("posts");
          }else {
              die("something went wrong");
          }
        }else{
            //coming without request
            redirect("posts");
        }

    }
}