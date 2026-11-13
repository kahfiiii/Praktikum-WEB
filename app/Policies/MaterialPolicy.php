<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    /**
     * Determine whether the user can update the material.
     */
    public function update(User $user, Material $material): bool
    {
        return (int) $material->user_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the material.
     */
    public function delete(User $user, Material $material): bool
    {
        return (int) $material->user_id === (int) $user->id;
    }
}
