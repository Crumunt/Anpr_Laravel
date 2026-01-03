<?php

namespace App\Services\Admin\Admins;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminWriteService
{
    public function delete(string $userId): bool
    {
        try {
            $user = User::findOrFail($userId);

            // Delete related data
            $user->details()->delete();
            $user->delete();

            Log::info('Admin account deleted', ['user_id' => $userId]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete admin', ['user_id' => $userId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function create(array $data): User
    {
        try {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_active' => true,
            ]);

            $user->assignRole($data['role'] ?? 'admin');

            if (isset($data['first_name']) || isset($data['last_name'])) {
                $user->details()->create([
                    'first_name' => $data['first_name'] ?? '',
                    'last_name' => $data['last_name'] ?? '',
                    'clsu_id' => $data['clsu_id'] ?? null,
                    'phone_number' => $data['phone_number'] ?? null,
                ]);
            }

            Log::info('Admin account created', ['user_id' => $user->id, 'role' => $data['role'] ?? 'admin']);

            return $user;
        } catch (\Exception $e) {
            Log::error('Failed to create admin', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update(string $userId, array $data): User
    {
        try {
            $user = User::findOrFail($userId);

            if (isset($data['email'])) {
                $user->email = $data['email'];
            }

            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            if (isset($data['is_active'])) {
                $user->is_active = $data['is_active'];
            }

            $user->save();

            if (isset($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            if ($user->details) {
                $user->details->update(array_filter([
                    'first_name' => $data['first_name'] ?? null,
                    'last_name' => $data['last_name'] ?? null,
                    'clsu_id' => $data['clsu_id'] ?? null,
                    'phone_number' => $data['phone_number'] ?? null,
                ]));
            }

            Log::info('Admin account updated', ['user_id' => $userId]);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update admin', ['user_id' => $userId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
