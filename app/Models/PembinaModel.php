<?php

namespace App\Models;

use CodeIgniter\Model;

class PembinaModel extends Model
{
    protected $table            = 'pembina';
    protected $primaryKey       = 'id_pembina';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nama_pembina',
        'no_hp',
        'username',
        'password',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'nama_pembina' => 'required|min_length[3]|max_length[120]',
        'username'     => 'required|min_length[3]|max_length[50]|is_unique[pembina.username,id_pembina,{id_pembina}]',
        'password'     => 'required|min_length[6]',
    ];

    /**
     * Ambil satu baris pembina berdasarkan username.
     */
    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Cek kecocokan username & password.
     * Mendukung dua kondisi:
     * - password di DB sudah di-hash (bcrypt/argon) -> pakai password_verify()
     * - password di DB masih plain text (seperti data awal 'admin123') -> fallback perbandingan langsung
     *
     * Mengembalikan data pembina (tanpa kolom password) jika valid, atau null jika gagal.
     */
    public function verifyLogin(string $username, string $password): ?array
    {
        $pembina = $this->findByUsername($username);

        if ($pembina === null) {
            return null;
        }

        $hashedOrPlain = $pembina['password'];
        $isValid       = false;

        // Deteksi apakah password di DB sudah berupa hash bcrypt/argon
        $isHashed = (bool) preg_match('/^\$2y\$|^\$argon2/', $hashedOrPlain);

        if ($isHashed) {
            $isValid = password_verify($password, $hashedOrPlain);
        } else {
            // Fallback untuk data awal yang masih plain text
            $isValid = hash_equals($hashedOrPlain, $password);
        }

        if (! $isValid) {
            return null;
        }

        unset($pembina['password']);

        return $pembina;
    }
}