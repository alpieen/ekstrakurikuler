<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PembinaModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected PembinaModel $pembinaModel;

    public function __construct()
    {
        $this->pembinaModel = new PembinaModel();
    }

    /**
     * Tampilkan halaman login.
     * Jika sudah login, langsung lempar ke dashboard.
     */
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Proses submit form login.
     */
    public function attemptLogin()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'password' => 'required|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/')
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $pembina = $this->pembinaModel->verifyLogin($username, $password);

        if ($pembina === null) {
            return redirect()->to('/')
                ->withInput()
                ->with('error', 'Username atau password salah.');
        }

        // Simpan data ke session
        session()->set([
            'logged_in'    => true,
            'id_pembina'   => $pembina['id_pembina'],
            'nama_pembina' => $pembina['nama_pembina'],
            'username'     => $pembina['username'],
        ]);

        return redirect()->to('/dashboard');
    }

    /**
     * Logout: hapus session dan kembali ke halaman login.
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}