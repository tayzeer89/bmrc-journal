<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Clear Permission Cache
        |--------------------------------------------------------------------------
        */

        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();


        /*
        |--------------------------------------------------------------------------
        | Create Roles
        |--------------------------------------------------------------------------
        */

        $editorialOfficer = Role::firstOrCreate([
            'name' => 'editorial_officer',
            'guard_name' => 'web',
        ]);

        $editorInChief = Role::firstOrCreate([
            'name' => 'editor_in_chief',
            'guard_name' => 'web',
        ]);

        $handlingEditor = Role::firstOrCreate([
            'name' => 'handling_editor',
            'guard_name' => 'web',
        ]);

        $financeOfficer = Role::firstOrCreate([
            'name' => 'finance_officer',
            'guard_name' => 'web',
        ]);

        $copyEditor = Role::firstOrCreate([
            'name' => 'copy_editor',
            'guard_name' => 'web',
        ]);

        $proofreader = Role::firstOrCreate([
            'name' => 'proofreader',
            'guard_name' => 'web',
        ]);

        $productionWebAdmin = Role::firstOrCreate([
            'name' => 'production_web_admin',
            'guard_name' => 'web',
        ]);

        $journalManager = Role::firstOrCreate([
            'name' => 'journal_manager',
            'guard_name' => 'web',
        ]);

        $systemAdministrator = Role::firstOrCreate([
            'name' => 'system_administrator',
            'guard_name' => 'web',
        ]);


        /*
        |--------------------------------------------------------------------------
        | System Administrator
        |--------------------------------------------------------------------------
        |
        | System Administrator gets ALL permissions.
        |
        */

        $systemAdministrator->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );


        /*
        |--------------------------------------------------------------------------
        | Editorial Officer
        |--------------------------------------------------------------------------
        */

        $editorialOfficer->syncPermissions([
            'dashboard.view',

            'manuscript.view',

            // Technical Check
            'technical_check.view',
            'technical_check.perform',
            'technical_check.assign',
            'technical_check.complete',
            'technical_check.return',
            'technical_check.history',

            // Similarity
            'similarity.view',
            'similarity.check',
            'similarity.approve',

            // Reviewer Management
            'reviewer.view',
            'reviewer.search',
            'reviewer.create',
            'reviewer.edit',
            'reviewer.approve',
            'reviewer.reject',
            'reviewer.request_update',
            'reviewer.suspend',
            'reviewer.activate',
            'reviewer.workload.view',
            'reviewer.history.view',
            'reviewer.performance.view',
            'reviewer.audit.view',

            // Payment Status
            'payment.view',
            'payment.create',
            'payment.edit',
            'payment.send',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Editor-in-Chief
        |--------------------------------------------------------------------------
        */

        $editorInChief->syncPermissions([
            'dashboard.view',

            'manuscript.view',

            // Editorial
            'editor.assign',
            'editor.assessment',
            'editor.recommend',
            'editor.decision',
            'editor.final_decision',

            // Reviewer oversight
            'reviewer.view',
            'reviewer.search',
            'reviewer.workload.view',
            'reviewer.history.view',
            'reviewer.performance.view',

            // Peer Review
            'review.view',

            // Revision
            'revision.view',

            // Reports
            'report.view',
            'report.export',

            // Audit
            'audit.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Handling Editor
        |--------------------------------------------------------------------------
        |
        | This is the important role for the sidebar you showed.
        |
        */

        $handlingEditor->syncPermissions([
            'dashboard.view',

            'manuscript.view',

            // My Assignments / Assessment
            'editor.assessment',
            'editor.recommend',

            // Reviewer Selection
            'reviewer.view',
            'reviewer.search',
            'reviewer.request',

            // Reviewer Invitation
            'reviewer.invite',
            'reviewer.invitation.view',
            'reviewer.invitation.remind',
            'reviewer.invitation.cancel',

            // Reviewer Assignment
            'reviewer.assign',
            'reviewer.reassign',
            'reviewer.remove',

            // Reviewer information
            'reviewer.workload.view',
            'reviewer.history.view',
            'reviewer.performance.view',

            // Peer Review
            'review.view',
            'review.create',
            'review.submit',
            'review.re_review',

            // Revision
            'revision.view',
            'revision.request',
            'revision.approve',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Finance Officer
        |--------------------------------------------------------------------------
        */

        $financeOfficer->syncPermissions([
            'dashboard.view',

            'finance.view',

            'payment.view',
            'payment.create',
            'payment.edit',
            'payment.send',
            'payment.verify',
            'payment.reject',

            'reviewer_payment.view',
            'reviewer_payment.create',
            'reviewer_payment.approve',
            'reviewer_payment.pay',

            'tax.view',
            'tax.manage',
            'tds.manage',

            'report.view',
            'report.export',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Copy Editor
        |--------------------------------------------------------------------------
        */

        $copyEditor->syncPermissions([
            'dashboard.view',
            'manuscript.view',

            'copyediting.view',
            'copyediting.edit',
            'copyediting.complete',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Proofreader
        |--------------------------------------------------------------------------
        */

        $proofreader->syncPermissions([
            'dashboard.view',
            'manuscript.view',

            'proofreading.view',
            'proofreading.edit',
            'proofreading.complete',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Production / Web Administrator
        |--------------------------------------------------------------------------
        */

        $productionWebAdmin->syncPermissions([
            'dashboard.view',

            'manuscript.view',

            'journal_website.view',
            'journal_website.manage',

            'production.view',
            'production.edit',
            'production.complete',
            'production.publish',

            'doi.view',
            'doi.create',
            'doi.edit',

            'issue.view',
            'issue.create',
            'issue.edit',
            'issue.delete',
            'issue.publish',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Journal Manager
        |--------------------------------------------------------------------------
        */

        $journalManager->syncPermissions([
            'dashboard.view',

            'manuscript.view',

            'technical_check.view',

            'similarity.view',

            'editor.assessment',

            'reviewer.view',
            'reviewer.search',
            'reviewer.workload.view',
            'reviewer.history.view',
            'reviewer.performance.view',

            'review.view',

            'revision.view',

            'payment.view',

            'copyediting.view',

            'proofreading.view',

            'production.view',

            'issue.view',

            'report.view',
            'report.export',

            'audit.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Clear Permission Cache Again
        |--------------------------------------------------------------------------
        */

        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();
    }
}