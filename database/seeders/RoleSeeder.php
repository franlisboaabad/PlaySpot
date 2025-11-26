<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Define los permisos del sistema agrupados por módulo
     */
    private function getPermissions(): array
    {
        return [
            'dashboard' => [
                'admin.home' => 'Ver Dashboard',
            ],
            'roles' => [
                'admin.roles.index' => 'Lista de roles',
                'admin.roles.create' => 'Registrar rol',
                'admin.roles.edit' => 'Editar rol',
                'admin.roles.destroy' => 'Eliminar rol',
            ],
            'usuarios' => [
                'admin.usuarios.index' => 'Lista de usuarios',
                'admin.usuarios.edit' => 'Editar usuario',
                'admin.usuarios.update' => 'Actualizar usuario y asignar roles',
            ],
            'canchas' => [
                'admin.canchas.index' => 'Lista de canchas',
                'admin.canchas.create' => 'Crear cancha',
                'admin.canchas.edit' => 'Editar cancha',
                'admin.canchas.destroy' => 'Eliminar cancha',
            ],
            'clientes' => [
                'admin.clientes.index' => 'Lista de clientes',
                'admin.clientes.create' => 'Crear cliente',
                'admin.clientes.edit' => 'Editar cliente',
                'admin.clientes.show' => 'Ver cliente',
                'admin.clientes.destroy' => 'Eliminar cliente',
            ],
            'reservas' => [
                'admin.reservas.index' => 'Lista de reservas',
                'admin.reservas.create' => 'Crear reserva',
                'admin.reservas.edit' => 'Editar reserva',
                'admin.reservas.show' => 'Ver reserva',
                'admin.reservas.destroy' => 'Eliminar reserva',
                'admin.reservas.calendario' => 'Ver calendario de reservas',
            ],
        ];
    }

    /**
     * Define los roles y sus permisos asociados
     */
    private function getRolePermissions(): array
    {
        return [
            'Admin' => ['*'], // El admin tiene acceso a todo
            'Colaborador' => ['admin.home'], // El colaborador solo tiene acceso al dashboard
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear roles
        $roles = [];
        foreach ($this->getRolePermissions() as $roleName => $permissions) {
            $roles[$roleName] = Role::firstOrCreate(['name' => $roleName]);
        }

        // Crear y sincronizar permisos
        foreach ($this->getPermissions() as $module => $modulePermissions) {
            foreach ($modulePermissions as $permissionName => $description) {
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['description' => $description]
                );

                // Asignar permisos a roles
                foreach ($this->getRolePermissions() as $roleName => $rolePermissions) {
                    if (in_array('*', $rolePermissions) || in_array($permissionName, $rolePermissions)) {
                        $roles[$roleName]->givePermissionTo($permission);
                    }
                }
            }
        }
    }
}
