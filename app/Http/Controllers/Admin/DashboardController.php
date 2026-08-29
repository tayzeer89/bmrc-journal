<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display permission-based dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Access
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->can('dashboard.view'),
            403,
            'You do not have permission to view the dashboard.'
        );

        /*
        |--------------------------------------------------------------------------
        | Available Dashboard Modules
        |--------------------------------------------------------------------------
        |
        | Only modules for which the user has at least one permission
        | will be displayed.
        |
        */

        $modules = [

            /*
            |--------------------------------------------------------------------------
            | Manuscript
            |--------------------------------------------------------------------------
            */
            'manuscript' => [
                'title' => 'Manuscript',
                'icon' => 'bi-file-earmark-text',
                'permissions' => [
                    'manuscript.view',
                    'manuscript.create',
                    'manuscript.edit',
                    'manuscript.submit',
                    'manuscript.delete',
                ],
                'route' => 'admin.manuscripts.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Technical Check
            |--------------------------------------------------------------------------
            */
            'technical_check' => [
                'title' => 'Technical Check',
                'icon' => 'bi-shield-check',
                'permissions' => [
                    'technical_check.view',
                    'technical_check.perform',
                    'technical_check.approve',
                    'technical_check.return',
                ],
                'route' => 'admin.technical-check.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Similarity
            |--------------------------------------------------------------------------
            */
            'similarity' => [
                'title' => 'Similarity Check',
                'icon' => 'bi-search',
                'permissions' => [
                    'similarity.view',
                    'similarity.check',
                    'similarity.approve',
                ],
                'route' => 'admin.similarity.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Editor
            |--------------------------------------------------------------------------
            */
            'editor' => [
                'title' => 'Editorial Management',
                'icon' => 'bi-person-workspace',
                'permissions' => [
                    'editor.assign',
                    'editor.assessment',
                    'editor.recommend',
                    'editor.decision',
                    'editor.final_decision',
                ],
                'route' => 'admin.editor.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Reviewer
            |--------------------------------------------------------------------------
            */
            'reviewer' => [
                'title' => 'Reviewer Management',
                'icon' => 'bi-people',
                'permissions' => [
                    'reviewer.view',
                    'reviewer.create',
                    'reviewer.edit',
                    'reviewer.invite',
                    'reviewer.assign',
                    'reviewer.remove',
                ],
                'route' => 'admin.reviewers.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Review
            |--------------------------------------------------------------------------
            */
            'review' => [
                'title' => 'Peer Review',
                'icon' => 'bi-journal-check',
                'permissions' => [
                    'review.view',
                    'review.create',
                    'review.submit',
                    'review.re_review',
                ],
                'route' => 'admin.reviews.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Revision
            |--------------------------------------------------------------------------
            */
            'revision' => [
                'title' => 'Revision',
                'icon' => 'bi-arrow-repeat',
                'permissions' => [
                    'revision.view',
                    'revision.request',
                    'revision.submit',
                    'revision.approve',
                ],
                'route' => 'admin.revisions.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */
            'payment' => [
                'title' => 'Payment',
                'icon' => 'bi-credit-card',
                'permissions' => [
                    'payment.view',
                    'payment.create',
                    'payment.verify',
                    'payment.refund',
                ],
                'route' => 'admin.payments.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Reviewer Payment
            |--------------------------------------------------------------------------
            */
            'reviewer_payment' => [
                'title' => 'Reviewer Payment',
                'icon' => 'bi-cash-stack',
                'permissions' => [
                    'reviewer_payment.view',
                    'reviewer_payment.create',
                    'reviewer_payment.approve',
                    'reviewer_payment.pay',
                ],
                'route' => 'admin.reviewer-payments.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Copyediting
            |--------------------------------------------------------------------------
            */
            'copyediting' => [
                'title' => 'Copyediting',
                'icon' => 'bi-pencil-square',
                'permissions' => [
                    'copyediting.view',
                    'copyediting.edit',
                    'copyediting.complete',
                ],
                'route' => 'admin.copyediting.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Proofreading
            |--------------------------------------------------------------------------
            */
            'proofreading' => [
                'title' => 'Proofreading',
                'icon' => 'bi-check2-square',
                'permissions' => [
                    'proofreading.view',
                    'proofreading.edit',
                    'proofreading.complete',
                ],
                'route' => 'admin.proofreading.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Production
            |--------------------------------------------------------------------------
            */
            'production' => [
                'title' => 'Production',
                'icon' => 'bi-printer',
                'permissions' => [
                    'production.view',
                    'production.edit',
                    'production.complete',
                    'production.publish',
                ],
                'route' => 'admin.production.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | DOI
            |--------------------------------------------------------------------------
            */
            'doi' => [
                'title' => 'DOI Management',
                'icon' => 'bi-link-45deg',
                'permissions' => [
                    'doi.view',
                    'doi.create',
                    'doi.edit',
                ],
                'route' => 'admin.doi.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Issue
            |--------------------------------------------------------------------------
            */
            'issue' => [
                'title' => 'Journal Issues',
                'icon' => 'bi-journal-bookmark',
                'permissions' => [
                    'issue.view',
                    'issue.create',
                    'issue.edit',
                    'issue.delete',
                    'issue.publish',
                ],
                'route' => 'admin.issues.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */
            'report' => [
                'title' => 'Reports',
                'icon' => 'bi-bar-chart',
                'permissions' => [
                    'report.view',
                    'report.export',
                ],
                'route' => 'admin.reports.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */
            'user' => [
                'title' => 'User Management',
                'icon' => 'bi-person-gear',
                'permissions' => [
                    'user.view',
                    'user.create',
                    'user.edit',
                    'user.delete',
                ],
                'route' => 'admin.users.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Roles
            |--------------------------------------------------------------------------
            */
            'role' => [
                'title' => 'Role Management',
                'icon' => 'bi-person-badge',
                'permissions' => [
                    'role.view',
                    'role.create',
                    'role.edit',
                    'role.delete',
                ],
                'route' => 'admin.roles.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Tax
            |--------------------------------------------------------------------------
            */
            'tax' => [
                'title' => 'Tax Management',
                'icon' => 'bi-percent',
                'permissions' => [
                    'tax.view',
                    'tax.manage',
                    'tds.manage',
                ],
                'route' => 'admin.tax.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Settings
            |--------------------------------------------------------------------------
            */
            'settings' => [
                'title' => 'Journal Settings',
                'icon' => 'bi-gear',
                'permissions' => [
                    'settings.view',
                    'settings.manage',
                ],
                'route' => 'admin.settings.index',
            ],

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */
            'audit' => [
                'title' => 'Audit Trail',
                'icon' => 'bi-clock-history',
                'permissions' => [
                    'audit.view',
                ],
                'route' => 'admin.audit.index',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Filter Modules According To Permissions
        |--------------------------------------------------------------------------
        */

        $visibleModules = collect($modules)
            ->filter(function ($module) use ($user) {

                foreach ($module['permissions'] as $permission) {

                    if ($user->can($permission)) {
                        return true;
                    }
                }

                return false;
            });

        return view(
            'admin.dashboard',
            compact(
                'user',
                'visibleModules'
            )
        );
    }
}
