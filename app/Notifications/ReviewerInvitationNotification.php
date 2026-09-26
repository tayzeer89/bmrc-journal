<?php

namespace App\Notifications;

use App\Models\ReviewerInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewerInvitationNotification extends Notification
{
    use Queueable;

    /*
    |--------------------------------------------------------------------------
    | Invitation
    |--------------------------------------------------------------------------
    */

    protected ReviewerInvitation $invitation;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        ReviewerInvitation $invitation
    ) {
        $this->invitation = $invitation;
    }


    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    */

    public function via(
        object $notifiable
    ): array {
        return [
            'mail',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Email Notification
    |--------------------------------------------------------------------------
    */

    public function toMail(
        object $notifiable
    ): MailMessage {

        /*
        |--------------------------------------------------------------------------
        | Load Required Relationships
        |--------------------------------------------------------------------------
        */

        $this->invitation->loadMissing([
            'manuscript',
            'inviter',
        ]);

        $invitation =
            $this->invitation;

        $manuscript =
            $invitation->manuscript;

        $inviter =
            $invitation->inviter;


        /*
        |--------------------------------------------------------------------------
        | Manuscript Information
        |--------------------------------------------------------------------------
        */

        $manuscriptNumber =
            $manuscript?->manuscript_id
            ??
            $manuscript?->id
            ??
            'N/A';

        $title =
            $manuscript?->title
            ??
            'N/A';


        /*
        |--------------------------------------------------------------------------
        | Invitation Date
        |--------------------------------------------------------------------------
        */

        $invitedAt =
            $invitation->invited_at
                ? $invitation
                    ->invited_at
                    ->format('d M Y')
                : 'N/A';


        /*
        |--------------------------------------------------------------------------
        | Invitation Response Deadline
        |--------------------------------------------------------------------------
        */

        $expiresAt =
            $invitation->expires_at
                ? $invitation
                    ->expires_at
                    ->format('d M Y')
                : 'N/A';


        /*
        |--------------------------------------------------------------------------
        | Review Submission Deadline
        |--------------------------------------------------------------------------
        |
        | BMRC Journal workflow:
        |
        | Invitation response period:
        | 3 days from invitation date.
        |
        | Review submission period:
        | 15 days from invitation date.
        |
        */

        $reviewDueDate =
            $invitation->invited_at
                ? $invitation
                    ->invited_at
                    ->copy()
                    ->addDays(15)
                    ->format('d M Y')
                : 'N/A';


        /*
        |--------------------------------------------------------------------------
        | Inviter
        |--------------------------------------------------------------------------
        */

        $inviterName =
            $inviter?->name
            ??
            'Handling Editor';


        /*
        |--------------------------------------------------------------------------
        | Secure Reviewer Invitation URL
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Do NOT send the reviewer directly to:
        |
        | reviewer.login
        |
        | or:
        |
        | reviewer.invitations.index
        |
        | The email should contain the unique invitation token.
        |
        | Example:
        |
        | http://127.0.0.1:8000/reviewer/invitation/{token}
        |
        | The public token gateway will:
        |
        | 1. Validate the invitation.
        | 2. Check expiry/cancellation.
        | 3. Save the token in the session.
        | 4. Redirect to Reviewer Login if necessary.
        | 5. Return the reviewer to this exact invitation.
        |
        */

        $invitationUrl =
            route(
                'reviewer.invitation.open',
                [
                    'token' =>
                        $invitation
                            ->invitation_token,
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | Build Email
        |--------------------------------------------------------------------------
        */

        return (new MailMessage)

            /*
            |--------------------------------------------------------------------------
            | Subject
            |--------------------------------------------------------------------------
            */

            ->subject(
                'Invitation to Review Manuscript - ' .
                $manuscriptNumber
            )


            /*
            |--------------------------------------------------------------------------
            | Greeting
            |--------------------------------------------------------------------------
            */

            ->greeting(
                'Dear ' .
                (
                    $notifiable->name
                    ??
                    'Reviewer'
                ) .
                ','
            )


            /*
            |--------------------------------------------------------------------------
            | Introduction
            |--------------------------------------------------------------------------
            */

            ->line(
                'You have been invited to review a manuscript submitted to the BMRC Journal.'
            )


            /*
            |--------------------------------------------------------------------------
            | Manuscript Details
            |--------------------------------------------------------------------------
            */

            ->line(
                'Manuscript ID: ' .
                $manuscriptNumber
            )

            ->line(
                'Title: ' .
                $title
            )

            ->line(
                'Invited By: ' .
                $inviterName
            )


            /*
            |--------------------------------------------------------------------------
            | Important Dates
            |--------------------------------------------------------------------------
            */

            ->line(
                'Invitation Date: ' .
                $invitedAt
            )

            ->line(
                'Invitation Response Deadline: ' .
                $expiresAt
            )

            ->line(
                'Review Submission Deadline: ' .
                $reviewDueDate
            )


            /*
            |--------------------------------------------------------------------------
            | Reviewer Instructions
            |--------------------------------------------------------------------------
            */

            ->line(
                'Please use the button below to view this review invitation.'
            )

            ->line(
                'If you are not currently logged in, you will be asked to sign in to your BMRC Journal reviewer account before accessing the invitation.'
            )


            /*
            |--------------------------------------------------------------------------
            | Invitation Button
            |--------------------------------------------------------------------------
            */

            ->action(
                'View Review Invitation',
                $invitationUrl
            )


            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */

            ->line(
                'After signing in, you will be able to review the invitation details and choose whether to accept or decline the invitation.'
            )

            ->line(
                'If you accept the invitation, you will be able to access the manuscript and submit your review through the reviewer portal.'
            )

            ->line(
                'If you are unable to review the manuscript, please decline the invitation so that another reviewer may be invited.'
            )


            /*
            |--------------------------------------------------------------------------
            | Security Information
            |--------------------------------------------------------------------------
            */

            ->line(
                'This invitation is intended specifically for you. Please do not forward this invitation email or share the invitation link with another person.'
            )


            /*
            |--------------------------------------------------------------------------
            | Salutation
            |--------------------------------------------------------------------------
            */

            ->salutation(
                "Regards,\n" .
                "Editorial Office\n" .
                "BMRC Journal\n" .
                "Bangladesh Medical Research Council (BMRC)"
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Array Representation
    |--------------------------------------------------------------------------
    */

    public function toArray(
        object $notifiable
    ): array {
        return [
            'reviewer_invitation_id' =>
                $this->invitation->id,

            'manuscript_id' =>
                $this->invitation
                    ->manuscript_id,

            'reviewer_id' =>
                $this->invitation
                    ->reviewer_id,

            'status' =>
                $this->invitation
                    ->status,

            'invitation_token' =>
                $this->invitation
                    ->invitation_token,
        ];
    }
}