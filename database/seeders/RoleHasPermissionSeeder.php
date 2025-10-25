<?php

namespace Database\Seeders;

use App\Models\Spatie\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleHasPermissionSeeder extends Seeder
{
    /**
    * Run the database seeds.
    */
    public function run(): void
    {
        $this->populateAdminRole();
        $this->populatePsychologistRole();
        $this->populateStudentRole();
        $this->populateDefaultRole();
    }
    
    private function populateAdminRole() : void
    {
        $adminRole = Role::whereName('admin')->first();
        $adminRole->givePermissionTo([
            'appointments.view.any',
            'appointments.moderate.any',
            'appointments.manage.any',
            'posts.view.any',
            'posts.moderate.any',
            'posts.manage.any',
            'profiles.view.any',
            'profiles.update.any',
            'users.view.any',
            'users.manage',
            'roles.manage',
            'permissions.manage',
            'blog.categories.manage',
            'blog.tags.manage',
            'files.manage.any',
            'reports.view',
            'settings.manage',
            'audit.view',
            'admins.invite',
            'helpdesk.categories.view.any',
            'helpdesk.categories.create',
            'helpdesk.categories.update',
            'helpdesk.categories.delete',
            'helpdesk.tickets.view.self',
            'helpdesk.tickets.view.any',
            'helpdesk.tickets.create',
            'helpdesk.tickets.update',
            'helpdesk.tickets.delete'
        ]);
    }
    
    private function populatePsychologistRole()
    {
        $psychologistRole = Role::whereName('psychologist')->first();
        $psychologistRole->givePermissionTo([
            'appointments.view.self',
            'appointments.view.assigned',
            'appointments.create.self',
            'appointments.update.self',
            'appointments.delete.self',
            'appointments.perform.assigned',
            
            'appointment.notes.create.assigned',
            'appointment.notes.update.self',
            
            'posts.view.any',
            'posts.view.self',
            'posts.create.self',
            'posts.update.self',
            'posts.delete.self',
            
            'profiles.view.self',
            'profiles.update.self',
            'files.manage.self',

            'helpdesk.categories.view.any',

            'helpdesk.tickets.view.self',
            'helpdesk.tickets.create',
            
        ]);
    }
    
    private function populateStudentRole()
    {
        $studentRole = Role::whereName('student')->first();
        $studentRole->givePermissionTo([
            'appointments.view.any',
            'appointments.book',
            'appointments.cancel.self',
            'appointments.view.self',
            
            'posts.view.any',
            
            'profiles.view.self',
            'profiles.update.self',
            'files.manage.self',

            'helpdesk.categories.view.any',

            'helpdesk.tickets.view.self',
            'helpdesk.tickets.create',
        ]);
    }
    
    private function populateDefaultRole()
    {
        $defaultRole = Role::whereName('default')->first();
        $defaultRole->givePermissionTo([
            'posts.view.any',
            'profiles.update.self',
            'appointments.view.any',

            'helpdesk.categories.view.any',

            'helpdesk.tickets.view.self',
            'helpdesk.tickets.create',
        ]);
        
    }
}
