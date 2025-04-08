<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any posts.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Qualquer usuário autenticado pode ver a lista de posts
        return $user->exists;
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Post $post)
    {
        // O post pode ser visto por:
        // 1. Dono do post
        // 2. Administradores
        // 3. Se o post estiver publicado
        return $post->is_published || $user->id === $post->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Qualquer usuário autenticado pode criar posts
        return $user->exists;
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Post $post)
    {
        // O post pode ser atualizado por:
        // 1. Dono do post
        // 2. Administradores
        // 3. Editores (se configurado)
        return $user->id === $post->user_id || $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Post $post)
    {
        // O post pode ser deletado por:
        // 1. Dono do post
        // 2. Administradores
        return $user->id === $post->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Post $post)
    {
        // Apenas administradores podem restaurar posts deletados
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Post $post)
    {
        // Apenas administradores podem deletar permanentemente
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can publish the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function publish(User $user, Post $post)
    {
        // Pode publicar:
        // 1. Dono do post
        // 2. Administradores
        // 3. Editores
        return $user->id === $post->user_id || $user->isAdmin() || $user->isEditor();
    }
}
