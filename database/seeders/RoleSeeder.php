<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $rolSuperUsuario = Role::create(['name' => 'Super Usuario']);
        $rolAdministrador = Role::create(['name' => 'Administrador']);
        $rolCapturista = Role::create(['name' => 'Capturista']);
        $rolConsultor = Role::create(['name' => 'Consultor']);


        Permission::create(['name' => 'administrar_catalogos'])->syncRoles([$rolSuperUsuario,$rolAdministrador]);

        Permission::create(['name' => 'subir_reportes'])->syncRoles([$rolSuperUsuario,$rolAdministrador]);
        Permission::create(['name' => 'ver_reportes'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolConsultor]);
        Permission::create(['name' => 'ver_soportes_promocionales'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista, $rolConsultor]);
        Permission::create(['name' => 'ver_medios_electronicos'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista, $rolConsultor]);
        Permission::create(['name' => 'ver_medios_impresos'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista, $rolConsultor]);
        Permission::create(['name' => 'ver_moviles'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista, $rolConsultor]);
        Permission::create(['name' => 'ver_radio'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista, $rolConsultor]);
        Permission::create(['name' => 'ver_television'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista, $rolConsultor]);
        Permission::create(['name' => 'ver_cine'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista, $rolConsultor]);

        Permission::create(['name' => 'administrar_roles_permisos'])->syncRoles([$rolSuperUsuario]);
        Permission::create(['name' => 'administrar_usuarios'])->syncRoles([$rolSuperUsuario,$rolAdministrador]);

        Permission::create(['name' => 'crear_medio'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista]);
        Permission::create(['name' => 'crear_datos_cualitativos'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista]);
        Permission::create(['name' => 'crear_testigo'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista, $rolConsultor]);
        Permission::create(['name' => 'editar_medio'])->syncRoles([$rolSuperUsuario,$rolAdministrador, $rolCapturista]);
        Permission::create(['name' => 'eliminar_medio'])->syncRoles([$rolSuperUsuario,$rolAdministrador]);
    }
}
