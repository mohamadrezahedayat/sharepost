<?php
class Posts extends Controller
{
    public function __construct()
    {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        // Get posts
        $posts = $this->postModel->getPosts();

        $data = [
            'posts' => $posts
        ];

        $this->view('posts/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // sanitize post array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => '',
            ];
            // validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please Enter title';
            }
            // validate bdy
            if (empty($data['body'])) {
                $data['body_err'] = 'Please Enter body text';
            }
            // make sure no errors
            if (empty($data['title_err']) && empty($data['body_err'])) {
                // Validated
                if ($this->postModel->addPost($data)) {
                    flash('post_message', 'POST Added');
                    redirect('posts');
                } else {
                    die('something went wrong');
                }
            } else {
                // load views with errors
                $this->view('posts/add', $data);
            }
        } else {
            $data = [
                'title' => '',
                'body' => ''
            ];

            $this->view('posts/add', $data);
        }
    }
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // sanitize post array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => '',
                'id' => $id,
            ];
            // validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please Enter title';
            }
            // validate body
            if (empty($data['body'])) {
                $data['body_err'] = 'Please Enter body text';
            }
            // make sure no errors
            if (empty($data['title_err']) && empty($data['body_err'])) {
                // Validated
                if ($this->postModel->updatePost($data)) {
                    flash('post_message', 'POST Updated');
                    redirect('posts');
                } else {
                    die('something went wrong');
                }
            } else {
                // load views with errors
                $this->view('posts/edit', $data);
            }
        } else {
            // get existing post from model
            $post = $this->postModel->getPostById($id);
            // check for owner
            if ($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }
            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body
            ];

            $this->view('posts/edit', $data);
        }
    }
    public function show($id)
    {
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);
        $data = [
            'post' => $post,
            'user' => $user,
        ];
        $this->view('posts/show', $data);
    }
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // get existing post from model
            $post = $this->postModel->getPostById($id);
            // check for owner
            if ($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }
            if ($this->postModel->deletePost($id)) {
                flash('post_message', 'POST Removed');
                redirect('posts');
            } else {
                die('something went wrong');
            }
        } else {
            redirect('posts');
        }
    }
}
