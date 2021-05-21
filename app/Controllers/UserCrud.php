<?php
namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;

class UserCrud extends Controller
{
    // show users list
    public function dashboard(){
    	$pager = \Config\Services::pager();

		$session = session();

        // if user not logged in
        if(!session()->get('logged_in')){
            // then redirct to login page
            return redirect()->to('/login_form');
        }
        $userModel = new UserModel();
        //$data['users'] = $userModel->orderBy('id', 'DESC')->findAll();
        //$data['name'] = $session->get('name');
        $data = [
            'users' => $userModel->orderBy('name', 'ASC')->paginate(5),
            'pager' => $userModel->pager,
            'name' =>  $session->get('name'),
        ];
        return view('user_view', $data);
    }
    public function login_form(){
        //$userModel = new UserModel();
        //$data['users'] = $userModel->orderBy('id', 'DESC')->findAll();
        return view('login_view');
    }
    public function auth()
    {
    	//print_r($_POST);die;
        $session = session();
        $userModel = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $data = $userModel->where('email', $email)->first();
        //echo $userModel->getLastQuery();die;
        if($data){
            $pass = $data['password'];
            //$verify_pass = password_verify(md5($password), $pass);
            //echo md5($password).'/'.$pass;die;
            if(md5($password)==$pass){
                $ses_data = [
                    'id'       => $data['id'],
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'logged_in'     => TRUE
                ];
                //print_r($ses_data);die;
                $session->set($ses_data);
                return redirect()->to('/dashboard');
            }else{
                $session->setFlashdata('msg', 'Wrong Password');
                return redirect()->to('/login_form');
            }
        }else{
            $session->setFlashdata('msg', 'Email not Found');
            return redirect()->to('/login_form');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login_form');
    }

    // add user form
    public function create(){
        $session = session();
        if(!session()->get('logged_in')){
            return redirect()->to('/login_form'); // then redirct to login page
        }
        return view('add_user');
    }

    // insert data
    public function store() {
        // if user not logged in
        if(!session()->get('logged_in')){
            return redirect()->to('/login_form'); // then redirct to login page
        }
        $userModel = new UserModel();
        $data = [
            'name' => $this->request->getVar('name'),
            'email'  => $this->request->getVar('email'),
        ];
        $userModel->insert($data);
        return $this->response->redirect(site_url('/dashboard'));
    }
    // insert data
    public function registration_form()
    {
        $session = session();
        $data = [];
        helper(['form']);

        if ($this->request->getMethod() == 'post') {
            //let's do the validation here
            $rules = [
                'name' => 'required|min_length[3]|max_length[20]',
                'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[8]|max_length[255]'
            ];

            if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;
                $session->setFlashdata('msg', 'Wrong Password');
            }else{
                $userModel = new UserModel();

                $newData = [
                    'name' => $this->request->getVar('name'),
                    'email' => $this->request->getVar('email'),
                    'password' => md5($this->request->getVar('password')),
                ];
                //print_r($newData);die;
                $userModel->insert($newData);
                //$model->save($newData);
                $session->setFlashdata('msg', 'Successful Registration');
                return redirect()->to('/registration_form');

            }
        }


        // echo view('templates/header', $data);
            echo view('registration_view');
        // echo view('templates/footer');
        //return redirect()->to('/login_form');
    }

    // show single user
    public function singleUser($id = null){
        // if user not logged in
        if(!session()->get('logged_in')){
            return redirect()->to('/login_form'); // then redirct to login page
        }
        $userModel = new UserModel();
        $data['user_obj'] = $userModel->where('id', $id)->first();
        //$data['user_obj'] = $userModel->find($user_id);

        return view('edit_view', $data);
    }

    // update user data
    public function update(){
        // if user not logged in
        if(!session()->get('logged_in')){
            return redirect()->to('/login_form'); // then redirct to login page
        }
        $userModel = new UserModel();
        $id = $this->request->getVar('id');
        $password = $this->request->getVar('password');
        /*if($password){
            $update_pwd = "'email'  => $password";
        }else{
            $update_pwd="";
        }
        */
        $data = [
            'name' => $this->request->getVar('name'),
            'email'  => $this->request->getVar('email'),
            
        ];
        //print_r($data);die;
        $userModel->update($id, $data);
        return $this->response->redirect(site_url('/dashboard'));
    }

    // delete user
    public function delete($id = null){
        // if user not logged in
        if(!session()->get('logged_in')){
            return redirect()->to('/login_form'); // then redirct to login page
        }
        $userModel = new UserModel();
        $data['user'] = $userModel->where('id', $id)->delete($id);
        return $this->response->redirect(site_url('/dashboard'));
    }

}