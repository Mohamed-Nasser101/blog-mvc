<?php
class Post{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getPosts(){
        $this->db->query("SELECT *,posts.created_at AS postCreated, posts.id AS postId
                                                    FROM posts
                                                    JOIN  users ON user_id = users.id
                                                    ORDER BY posts.created_at DESC ");
        $posts = $this->db->resultSet();
        return $posts;
    }
    public function addPost($title,$body,$user_id){
        $this->db->query("insert into posts(title,body,user_id) values(:title,:body,:id)");
        $this->db->bind(":title",$title);
        $this->db->bind(":body",$body);
        $this->db->bind(":id",$user_id);
       return $this->db->execute();
    }
    public function getPostById($id){
        $this->db->query("SELECT username,title,body,posts.created_at AS timeCreated,user_id,posts.id AS postId FROM posts
                                                  JOIN users ON posts.user_id = users.id
                                                    WHERE posts.id = :id");
        $this->db->bind(":id",$id);
        $post = $this->db->single();
        return $post;
    }
    public function editPost($id,$title,$body){
        $this->db->query("UPDATE posts SET title = :title,body = :body WHERE id = :id");
        $this->db->bind(":title",$title);
        $this->db->bind(":body",$body);
        $this->db->bind(":id",$id);
        return $this->db->execute();
    }
    public function deletePost($id){
        $this->db->query("DELETE FROM posts WHERE id = :id");
        $this->db->bind(":id",$id);
        return $this->db->execute();
    }
}