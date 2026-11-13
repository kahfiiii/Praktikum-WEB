<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    /**
     * Determine whether the user can view the quiz.
     */
    public function view(User $user, Quiz $quiz): bool
    {
        if ($quiz->status === 'draft') {
            return (int) $quiz->user_id === (int) $user->id;
        }

        return true;
    }

    /**
     * Determine whether the user can update the quiz.
     */
    public function update(User $user, Quiz $quiz): bool
    {
        return (int) $quiz->user_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the quiz.
     */
    public function delete(User $user, Quiz $quiz): bool
    {
        return (int) $quiz->user_id === (int) $user->id;
    }

    /**
     * Determine whether the user can publish the quiz.
     */
    public function publish(User $user, Quiz $quiz): bool
    {
        return (int) $quiz->user_id === (int) $user->id;
    }
}
