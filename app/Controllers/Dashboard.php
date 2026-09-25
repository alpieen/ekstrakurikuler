<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'title'        => 'Dashboard',
            'nama_petugas' => session()->get('nama_pembina'),
        ];

        $data['content'] = view('dashboard_content', $data);

        return view('layouts/main', $data);
    }
}