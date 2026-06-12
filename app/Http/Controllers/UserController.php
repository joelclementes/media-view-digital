<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private array $rolesSuperUsuario = [
        'Super usuario',
        'SuperUsuario',
        'super usuario',
        'superusuario',
    ];

    public function index()
    {
        $usuarioAutenticado = auth()->user();

        $roles = Role::query()
            ->when(
                $usuarioAutenticado->hasRole('Administrador'),
                fn ($query) => $query->whereNotIn('name', $this->rolesSuperUsuario)
            )
            ->orderBy('name')
            ->get();

        $users = User::query()
            ->with('roles')
            ->when(
                $usuarioAutenticado->hasRole('Administrador'),
                fn ($query) => $query->whereDoesntHave(
                    'roles',
                    fn ($subQuery) => $subQuery->whereIn('name', $this->rolesSuperUsuario)
                )
            )
            ->orderBy('name')
            ->get();

        return view('usuarios.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $rolesPermitidos = $this->rolesPermitidos();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'string', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in($rolesPermitidos)],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $user)
    {
        if (
            auth()->user()->hasRole('Administrador')
            && $user->roles()->whereIn('name', $this->rolesSuperUsuario)->exists()
        ) {
            abort(403);
        }

        $rolesPermitidos = $this->rolesPermitidos();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'string',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in($rolesPermitidos)],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    private function rolesPermitidos(): array
    {
        return Role::query()
            ->when(
                auth()->user()->hasRole('Administrador'),
                fn ($query) => $query->whereNotIn('name', $this->rolesSuperUsuario)
            )
            ->pluck('name')
            ->toArray();
    }
}
