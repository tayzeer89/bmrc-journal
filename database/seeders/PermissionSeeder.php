<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            'dashboard.view',

            // Users
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            // Roles
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',

            // Manuscripts
            'manuscript.view',
            'manuscript.create',
            'manuscript.edit',
            'manuscript.submit',
            'manuscript.delete',

            // Technical Screening
            'technical_check.view',
            'technical_check.perform',
            'technical_check.assign',
            'technical_check.complete',
            'technical_check.return',
            'technical_check.override',
            'technical_check.history',



            // Similarity
            'similarity.view',
            'similarity.check',
            'similarity.approve',

            // Editorial
            'editor.assign',
            'editor.assessment',
            'editor.recommend',
            'editor.decision',
            'editor.final_decision',

            // Reviewers
            'reviewer.view',
            'reviewer.create',
            'reviewer.edit',
            'reviewer.invite',
            'reviewer.assign',
            'reviewer.remove',

            // Reviews
            'review.view',
            'review.create',
            'review.submit',
            'review.re_review',

            // Revision
            'revision.view',
            'revision.request',
            'revision.submit',
            'revision.approve',


            // Payments
            'payment.view',
            'payment.create',
            'payment.verify',
            'payment.refund',

            // Finance Dashboard
            'finance.view',

            // Reviewer Payment
            'reviewer_payment.view',
            'reviewer_payment.create',
            'reviewer_payment.approve',
            'reviewer_payment.pay',

            // Tax
            'tax.view',
            'tax.manage',
            'tds.manage',

            // Copyediting
            'copyediting.view',
            'copyediting.edit',
            'copyediting.complete',

            // Proofreading
            'proofreading.view',
            'proofreading.edit',
            'proofreading.complete',

            // Production
            'production.view',
            'production.edit',
            'production.complete',
            'production.publish',

            // DOI
            'doi.view',
            'doi.create',
            'doi.edit',

            // Journal
            'issue.view',
            'issue.create',
            'issue.edit',
            'issue.delete',
            'issue.publish',

            // Reports
            'report.view',
            'report.export',

            // Audit
            'audit.view',

            // Settings
            'settings.view',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}