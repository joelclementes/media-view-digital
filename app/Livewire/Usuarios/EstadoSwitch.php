<?php

namespace App\Livewire\Usuarios;

use App\Models\User;
use Livewire\Component;

class EstadoSwitch extends Component
{
    public User $user;

    public function toggle()
    {
        if (auth()->id() === $this->user->id) {
            return;
        }

        $this->user->activo = ! $this->user->activo;
        $this->user->save();

        $this->user->refresh();
    }

    public function render()
    {
        return view('livewire.usuarios.estado-switch');
    }
}