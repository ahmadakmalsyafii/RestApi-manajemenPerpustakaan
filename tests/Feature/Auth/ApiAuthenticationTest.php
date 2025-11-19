<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

// Gunakan trait ini agar database di-reset setelah setiap tes
uses(RefreshDatabase::class);

// TES 1: Registrasi Berhasil
test('user can register successfully', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    // Kirim request POST ke endpoint
    $response = $this->postJson('/api/register', $userData);

    // Berdasarkan AuthController.php, status sukses adalah 201
    $response->assertStatus(201);

    // Pastikan struktur JSON respons sesuai
    $response->assertJsonStructure([
        'user' => ['id', 'name', 'email'],
        'token'
    ]);

    // Pastikan data benar-benar tersimpan di database
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

// TES 2: Registrasi Gagal (Validasi Error)
test('registration fails if password confirmation does not match', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password_salah', // Password salah
    ]);

    // Berdasarkan AuthController.php, status validasi adalah 422
    $response->assertStatus(422);

    // Pastikan ada pesan error
    $response->assertJsonStructure(['error']);
});

// TES 3: Login Berhasil
test('user can login with correct credentials', function () {
    // 1. Buat user dulu menggunakan factory
    $user = User::factory()->create([
        'email' => 'login@example.com',
        'password' => Hash::make('password123'),
    ]);

    // 2. Coba login dengan kredensial yang benar
    $response = $this->postJson('/api/login', [
        'email' => 'login@example.com',
        'password' => 'password123',
    ]);

    // 3. Assertions
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'access_token',
        'token_type',
        'expires_in',
        'user' => ['id', 'name', 'email'],
    ]);
});

// TES 4: Login Gagal (Kredensial Salah)
test('login fails with incorrect credentials', function () {
    // 1. Buat user
    User::factory()->create([
        'email' => 'login@example.com',
        'password' => Hash::make('password123'),
    ]);

    // 2. Coba login dengan password salah
    $response = $this->postJson('/api/login', [
        'email' => 'login@example.com',
        'password' => 'password_salah',
    ]);

    // 3. Assertions
    // Berdasarkan AuthController.php, status error adalah 401
    $response->assertStatus(401);
    $response->assertJson(['error' => 'Invalid credentials']);
});
