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
        $rolAdministrador = Role::create(['name' => 'Administrador']);
        $rolUsuario = Role::create(['name' => 'Usuario']);
        $rolConsultor = Role::create(['name' => 'Consultor']);


        Permission::create(['name' => 'configurar_sistema'])->syncRoles([$rolAdministrador]);
        Permission::create(['name' => 'administrar_catalogos'])->syncRoles([$rolAdministrador]);
        Permission::create(['name' => 'borrar_registros'])->syncRoles([$rolAdministrador]);

        Permission::create(['name' => 'subir_reportes'])->syncRoles([$rolAdministrador, $rolUsuario]);
        Permission::create(['name' => 'ver_reportesr'])->syncRoles([$rolAdministrador, $rolUsuario]);
        Permission::create(['name' => 'registrar_medios_alternos'])->syncRoles([$rolAdministrador, $rolUsuario]);
        Permission::create(['name' => 'registrar_medios_electronicos'])->syncRoles([$rolAdministrador, $rolUsuario]);
        Permission::create(['name' => 'registrar_medios_impresos'])->syncRoles([$rolAdministrador, $rolUsuario]);
        Permission::create(['name' => 'registrar_moviles'])->syncRoles([$rolAdministrador, $rolUsuario]);
        Permission::create(['name' => 'registrar_radio'])->syncRoles([$rolAdministrador, $rolUsuario]);
        Permission::create(['name' => 'registrar_television'])->syncRoles([$rolAdministrador, $rolUsuario]);
        Permission::create(['name' => 'registrar_cine'])->syncRoles([$rolAdministrador, $rolUsuario]);

        Permission::create(['name' => 'crear_medio'])->syncRoles([$rolAdministrador, $rolUsuario]);
        Permission::create(['name' => 'editar_medio'])->syncRoles([$rolAdministrador, $rolUsuario]);
        Permission::create(['name' => 'eliminar_medio'])->syncRoles([$rolAdministrador]);
        Permission::create(['name' => 'crear_testigo'])->syncRoles([$rolAdministrador, $rolUsuario, $rolConsultor]);
    }
}
