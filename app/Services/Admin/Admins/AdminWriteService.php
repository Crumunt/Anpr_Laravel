<?php

namespace App\Services\Admin\Admins;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminWriteService
{
    /**
     * Delete a single admin user
     */
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

    /**
     * Bulk delete multiple admin users
     *
     * @param array $userIds Array of user IDs to delete
     * @return array Result with success count and failed IDs
     */
    public function bulkDelete(array $userIds): array
    {
        $successCount = 0;
        $failedIds = [];

        DB::beginTransaction();

        try {
            foreach ($userIds as $userId) {
                try {
                    $user = User::find($userId);

                    if (!$user) {
                        $failedIds[] = $userId;
                        continue;
                    }

                    // Prevent deletion of super_admin by non-super_admin (handled in component)
                    $user->details()->delete();
                    $user->delete();
                    $successCount++;
                } catch (\Exception $e) {
                    Log::warning('Failed to delete admin in bulk operation', [
                        'user_id' => $userId,
                        'error' => $e->getMessage()
                    ]);
                    $failedIds[] = $userId;
                }
            }

            DB::commit();

            Log::info('Bulk admin deletion completed', [
                'success_count' => $successCount,
                'failed_count' => count($failedIds)
            ]);

            return [
                'success_count' => $successCount,
                'failed_ids' => $failedIds,
                'total' => count($userIds)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk admin deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Bulk activate multiple admin users
     *
     * @param array $userIds Array of user IDs to activate
     * @return array Result with success count and failed IDs
     */
    public function bulkActivate(array $userIds): array
    {
        return $this->bulkUpdateStatus($userIds, true);
    }

    /**
     * Bulk deactivate multiple admin users
     *
     * @param array $userIds Array of user IDs to deactivate
     * @return array Result with success count and failed IDs
     */
    public function bulkDeactivate(array $userIds): array
    {
        return $this->bulkUpdateStatus($userIds, false);
    }

    /**
     * Internal method to bulk update activation status
     */
    private function bulkUpdateStatus(array $userIds, bool $isActive): array
    {
        $successCount = 0;
        $failedIds = [];

        DB::beginTransaction();

        try {
            foreach ($userIds as $userId) {
                try {
                    $user = User::find($userId);

                    if (!$user) {
                        $failedIds[] = $userId;
                        continue;
                    }

                    $user->is_active = $isActive;
                    $user->save();
                    $successCount++;
                } catch (\Exception $e) {
                    Log::warning('Failed to update admin status in bulk operation', [
                        'user_id' => $userId,
                        'is_active' => $isActive,
                        'error' => $e->getMessage()
                    ]);
                    $failedIds[] = $userId;
                }
            }

            DB::commit();

            $action = $isActive ? 'activation' : 'deactivation';
            Log::info("Bulk admin {$action} completed", [
                'success_count' => $successCount,
                'failed_count' => count($failedIds)
            ]);

            return [
                'success_count' => $successCount,
                'failed_ids' => $failedIds,
                'total' => count($userIds)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk admin status update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Bulk reset passwords for multiple admin users
     *
     * @param array $userIds Array of user IDs
     * @param string|null $newPassword Optional password (generates random if null)
     * @return array Result with success count and generated passwords
     */
    public function bulkResetPassword(array $userIds, ?string $newPassword = null): array
    {
        $successCount = 0;
        $failedIds = [];
        $results = [];

        DB::beginTransaction();

        try {
            foreach ($userIds as $userId) {
                try {
                    $user = User::find($userId);

                    if (!$user) {
                        $failedIds[] = $userId;
                        continue;
                    }

                    $password = $newPassword ?? $this->generateRandomPassword();
                    $user->password = Hash::make($password);
                    $user->must_change_password = true;
                    $user->save();

                    $results[$userId] = [
                        'email' => $user->email,
                        'temp_password' => $password
                    ];

                    $successCount++;
                } catch (\Exception $e) {
                    Log::warning('Failed to reset password in bulk operation', [
                        'user_id' => $userId,
                        'error' => $e->getMessage()
                    ]);
                    $failedIds[] = $userId;
                }
            }

            DB::commit();

            Log::info('Bulk password reset completed', [
                'success_count' => $successCount,
                'failed_count' => count($failedIds)
            ]);

            return [
                'success_count' => $successCount,
                'failed_ids' => $failedIds,
                'total' => count($userIds),
                'results' => $results
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk password reset failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Generate a random secure password
     */
    private function generateRandomPassword(int $length = 12): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        return substr(str_shuffle($chars), 0, $length);
    }
}
