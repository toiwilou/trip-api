<?php 

namespace App\Traits;

trait UserTrait
{
    public function getRoles(string $role): array
    {
        $roles = [
            'ROLE_MANAGER' => ['ROLE_MANAGER', 'ROLE_SUPER_ADMIN', 'ROLE_ADMIN'],
            'ROLE_SUPER_ADMIN' => ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN'],
            'ROLE_ADMIN' => ['ROLE_ADMIN']
        ];

        return $roles[$role];
    }
}
