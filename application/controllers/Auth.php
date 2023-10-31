<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function index()
	{
		$this->load->view('login');
	}	
	public function login()
	{
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));
		$this->db->from('user');
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$cek = $this->db->get()->row();
		if ($cek == NULL) {
			$this->session->set_flashdata('alert', '<div class="alert alert-secondary alert-dismissible" role="alert">
		Okay you are succes from creating account
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	    	</div>');
			redirect('auth');
		} else {
			$data = array(
				'username' => $cek->username,
				'nama' => $cek->nama,
				'level' => $cek->level,
				'alamat' => $cek->alamat
			);
			$this->session->set_userdata($data);
			if ($this->session->userdata('level') == 'Admin') {
				redirect('admin/home');
			} else if ($this->session->userdata('level') == 'petugas') {
				redirect('admin/home');
			} else {
				redirect('masyarakat/home');
			}
		}
	}
	public function regist()
	{

		$this->load->view('register');
	}
	public function simpan()
	{
		$this->db->from('user')->where('username', $this->input->post('username'));
		$cek = $this->db->get()->row();
		if ($cek == null) {
			$data = array(
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'level' => 'masyarakat'
			);
			$this->db->insert('user', $data);
			$this->session->set_flashdata('alert', '<div class="alert alert-secondary alert-dismissible" role="alert">
		Okay you are succes from creating account
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	    	</div>');
			redirect('auth');
		} else {
			$this->session->set_flashdata('alert', '<div class="alert alert-secondary alert-dismissible" role="alert">
		Sorry but the username is already in use
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	    	</div>');
			redirect('auth/regist');
		}
	}
}
