<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Dijalankan sebelum controller diakses.
     * Jika belum login (session 'id_pembina' tidak ada), tendang ke halaman login.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('logged_in')) {
            $session->setFlashdata('error', 'Silakan login terlebih dahulu.');

            return redirect()->to('/');
        }
    }

    /**
     * Dijalankan setelah controller diakses.
     * Tidak perlu melakukan apa-apa di sini untuk kasus ini.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak digunakan
    }
}