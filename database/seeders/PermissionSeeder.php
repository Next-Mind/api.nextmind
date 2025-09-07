<?php

namespace Database\Seeders;

use App\Models\Spatie\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
    * Run the database seeds.
    */
    public function run(): void
    {
        $permissions = [
            // Appointments 
            'appointments.view.any',
            'appointments.view.self',
            'appointments.view.assigned',
            'appointments.create.self',
            'appointments.update.self',
            'appointments.delete.self',
            'appointments.book', 
            'appointments.cancel.self',
            'appointments.perform.assigned',
            'appointments.moderate.any',
            'appointments.manage.any', 
            
            // Appointment Notes / Prontuários
            'appointment.notes.create.assigned',
            'appointment.notes.update.self',
            'appointment.notes.view.assigned',
            
            // Posts (blog)
            'posts.view.any',
            'posts.view.self',
            'posts.create.self',
            'posts.update.self',
            'posts.delete.self',
            'posts.moderate.any',
            'posts.manage.any',
            
            // Blog taxonomy
            'blog.categories.manage',
            'blog.tags.manage',
            
            // Profiles
            'profiles.view.self',
            'profiles.view.any',
            'profiles.update.self',
            'profiles.update.any',
            
            // Users / Roles / Permissions
            'users.view.any',
            'users.manage',
            'roles.manage',
            'permissions.manage',
            
            // Files
            'files.manage.self',
            'files.manage.any',
            
            // Reports / Settings / Audit
            'reports.view',
            'settings.manage',
            'audit.view',

            //Admin Permissions
            'admins.invite',
        ];
        
        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }
    }
}
