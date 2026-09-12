<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
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
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            'dashboard.view',


            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */

            'user.view',
            'user.create',
            'user.edit',
            'user.delete',


            /*
            |--------------------------------------------------------------------------
            | Roles & Permissions
            |--------------------------------------------------------------------------
            */

            'role.view',
            'role.create',
            'role.edit',
            'role.delete',


            /*
            |--------------------------------------------------------------------------
            | Journal Website / Public Website Content
            |--------------------------------------------------------------------------
            */

            'journal_website.view',
            'journal_website.manage',
            
            /*
            |--------------------------------------------------------------------------
            | Manuscripts
            |--------------------------------------------------------------------------
            */

            'manuscript.view',
            'manuscript.create',
            'manuscript.edit',
            'manuscript.submit',
            'manuscript.delete',


            /*
            |--------------------------------------------------------------------------
            | Technical Screening
            |--------------------------------------------------------------------------
            */

            'technical_check.view',
            'technical_check.perform',
            'technical_check.assign',
            'technical_check.complete',
            'technical_check.return',
            'technical_check.override',
            'technical_check.history',


            /*
            |--------------------------------------------------------------------------
            | Similarity Check
            |--------------------------------------------------------------------------
            */

            'similarity.view',
            'similarity.check',
            'similarity.approve',


            /*
            |--------------------------------------------------------------------------
            | Editorial
            |--------------------------------------------------------------------------
            */

            'editor.assign',
            'editor.assessment',
            'editor.recommend',
            'editor.decision',
            'editor.final_decision',


            /*
            |--------------------------------------------------------------------------
            | Reviewer Management
            |--------------------------------------------------------------------------
            */

            // General reviewer access
            'reviewer.view',

            // Search approved reviewer pool
            'reviewer.search',

            // Handling Editor requests a new reviewer
            'reviewer.request',

            // Editorial Officer creates basic reviewer account
            'reviewer.create',

            // Edit basic reviewer information
            'reviewer.edit',

            // Reviewer approval workflow
            'reviewer.approve',
            'reviewer.reject',
            'reviewer.request_update',

            // Reviewer account management
            'reviewer.suspend',
            'reviewer.activate',

            // Reviewer invitation
            'reviewer.invite',
            'reviewer.invitation.view',
            'reviewer.invitation.remind',
            'reviewer.invitation.cancel',

            // Reviewer assignment
            'reviewer.assign',
            'reviewer.reassign',
            'reviewer.remove',

            // Reviewer history / workload / performance
            'reviewer.workload.view',
            'reviewer.history.view',
            'reviewer.performance.view',

            // Reviewer profile audit
            'reviewer.audit.view',


            /*
            |--------------------------------------------------------------------------
            | Peer Review
            |--------------------------------------------------------------------------
            */

            'review.view',
            'review.create',
            'review.submit',
            'review.re_review',


            /*
            |--------------------------------------------------------------------------
            | Revision
            |--------------------------------------------------------------------------
            */

            'revision.view',
            'revision.request',
            'revision.submit',
            'revision.approve',


            /*
            |--------------------------------------------------------------------------
            | Article Payments
            |--------------------------------------------------------------------------
            */

            'payment.view',
            'payment.create',
            'payment.edit',
            'payment.send',
            'payment.verify',
            'payment.reject',


            /*
            |--------------------------------------------------------------------------
            | Finance Dashboard
            |--------------------------------------------------------------------------
            */

            'finance.view',


            /*
            |--------------------------------------------------------------------------
            | Reviewer Payment
            |--------------------------------------------------------------------------
            */

            'reviewer_payment.view',
            'reviewer_payment.create',
            'reviewer_payment.approve',
            'reviewer_payment.pay',


            /*
            |--------------------------------------------------------------------------
            | Tax
            |--------------------------------------------------------------------------
            */

            'tax.view',
            'tax.manage',
            'tds.manage',


            /*
            |--------------------------------------------------------------------------
            | Copyediting
            |--------------------------------------------------------------------------
            */

            'copyediting.view',
            'copyediting.edit',
            'copyediting.complete',


            /*
            |--------------------------------------------------------------------------
            | Proofreading
            |--------------------------------------------------------------------------
            */

            'proofreading.view',
            'proofreading.edit',
            'proofreading.complete',


            /*
            |--------------------------------------------------------------------------
            | Production
            |--------------------------------------------------------------------------
            */

            'production.view',
            'production.edit',
            'production.complete',
            'production.publish',


            /*
            |--------------------------------------------------------------------------
            | DOI
            |--------------------------------------------------------------------------
            */

            'doi.view',
            'doi.create',
            'doi.edit',


            /*
            |--------------------------------------------------------------------------
            | Journal / Issue
            |--------------------------------------------------------------------------
            */

            'issue.view',
            'issue.create',
            'issue.edit',
            'issue.delete',
            'issue.publish',


            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */

            'report.view',
            'report.export',


            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            'audit.view',


            /*
            |--------------------------------------------------------------------------
            | Settings
            |--------------------------------------------------------------------------
            */

            'settings.view',
            'settings.manage',
        ];


        /*
        |--------------------------------------------------------------------------
        | Create Permissions
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'web',
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Clear Cache Again
        |--------------------------------------------------------------------------
        */

        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();
    }
}