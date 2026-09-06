<?php

namespace App\Http\Controllers\Admin;

use App\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();

        // Un admin ne peut pas modifier un admin ou un super-admin
        if (
            $currentUser->hasRole('admin') &&
            $user->hasAnyRole(['admin', 'super-admin'])
        ) {
            abort(403, 'Vous n’avez pas l’autorisation de modifier cet utilisateur.');
        }

        // Personne ne peut modifier son propre rôle
        if ($currentUser->id === $user->id) {
            abort(403, 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();

        // Personne ne peut modifier son propre rôle
        if ($currentUser->id === $user->id) {
            abort(403, 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        // Un admin ne peut pas modifier un admin ou un super-admin
        if (
            $currentUser->hasRole('admin') &&
            $user->hasAnyRole(['admin', 'super-admin'])
        ) {
            abort(403, 'Vous n’avez pas l’autorisation de modifier cet utilisateur.');
        }

        $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        $oldRole = $user->getRoleNames()->first();
        $newRole = $request->role;

        $user->syncRoles([$newRole]);

        ActivityLogger::log(
            'user.role_updated',
            'Rôle de l’utilisateur modifié : ' . $user->name,
            $user,
            [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'old_role' => $oldRole,
                'new_role' => $newRole,
            ]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Le rôle de l’utilisateur a été mis à jour.');
    }


    public function destroy(User $user)
    {
        $currentUser = auth()->user();

        // Personne ne peut supprimer son propre compte
        if ($currentUser->id === $user->id) {
            abort(403, 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Seul le super-admin peut supprimer un utilisateur
        if (!$currentUser->hasRole('super-admin')) {
            abort(403, 'Seul le super-admin peut supprimer un utilisateur.');
        }

        // Le super-admin ne peut pas supprimer un autre super-admin
        if ($user->hasRole('super-admin')) {
            abort(403, 'Un super-admin ne peut pas supprimer un autre super-admin.');
        }

        // Vérifier si l'utilisateur possède déjà une activité
        $hasActivity =
            $user->stockMovements()->exists() ||
            $user->inventories()->exists() ||
            $user->sales()->exists();

        if ($hasActivity) {
            return redirect()
                ->route('admin.users.index')
                ->with(
                    'error',
                    'Impossible de supprimer cet utilisateur car il possède déjà une activité dans AfricStock.'
                );
        }

        ActivityLogger::log(
            'user.deleted',
            'Utilisateur supprimé : ' . $user->name,
            $user,
            [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'role' => $user->getRoleNames()->first(),
            ]
        );


        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function deactivate(User $user)
    {
        $currentUser = auth()->user();

        // Personne ne peut désactiver son propre compte
        if ($currentUser->id === $user->id) {
            abort(403, 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        // Seul le super-admin peut désactiver un compte
        if (!$currentUser->hasRole('super-admin')) {
            abort(403, 'Seul le super-admin peut désactiver un utilisateur.');
        }

        // Un super-admin ne peut pas être désactivé
        if ($user->hasRole('super-admin')) {
            abort(403, 'Un super-admin ne peut pas être désactivé.');
        }

        $user->update([
            'is_active' => false,
        ]);

        ActivityLogger::log(
            'user.deactivated',
            'Utilisateur désactivé : ' . $user->name,
            $user,
            [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'role' => $user->getRoleNames()->first(),
            ]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Le compte de l’utilisateur a été désactivé. Son historique est conservé.');
    }

    public function activate(User $user)
    {
        $currentUser = auth()->user();

        // Seul le super-admin peut réactiver un compte
        if (!$currentUser->hasRole('super-admin')) {
            abort(403, 'Seul le super-admin peut réactiver un utilisateur.');
        }

        // Un super-admin n'a pas besoin d'être réactivé
        if ($user->hasRole('super-admin')) {
            abort(403, 'Le compte du super-admin ne peut pas être modifié.');
        }

        $user->update([
            'is_active' => true,
        ]);

        ActivityLogger::log(
            'user.activated',
            'Utilisateur réactivé : ' . $user->name,
            $user,
            [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'role' => $user->getRoleNames()->first(),
            ]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Le compte de l’utilisateur a été réactivé.');
    }
}
