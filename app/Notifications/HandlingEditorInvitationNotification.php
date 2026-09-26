<?php

namespace App\Notifications;

use App\Models\EditorAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HandlingEditorInvitationNotification extends Notification
{
    use Queueable;

    protected EditorAssignment $assignment;

    /**
     * Create a new notification instance.
     */
    public function __construct(EditorAssignment $assignment)
    {
        $this->assignment = $assignment;
    }

    /**
     * Get the notification delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the email.
     */
    public function toMail(object $notifiable): MailMessage
    {
        /*
        |--------------------------------------------------------------------------
        | Load relationships
        |--------------------------------------------------------------------------
        */

        $this->assignment->loadMissing([
            'manuscript',
            'assignedBy',
        ]);

        $assignment = $this->assignment;
        $manuscript = $assignment->manuscript;
        $assignedBy = $assignment->assignedBy;


        /*
        |--------------------------------------------------------------------------
        | Manuscript Information
        |--------------------------------------------------------------------------
        */

        $manuscriptNumber =
            $manuscript->manuscript_id
            ?? $manuscript->id
            ?? 'N/A';

        $title =
            $manuscript->title
            ?? 'N/A';


        /*
        |--------------------------------------------------------------------------
        | Due Date
        |--------------------------------------------------------------------------
        */

        $dueDate = $assignment->due_date
            ? $assignment->due_date->format('d M Y')
            : 'Not specified';


        /*
        |--------------------------------------------------------------------------
        | Assignment Note
        |--------------------------------------------------------------------------
        */

        $assignmentNote =
            $assignment->assignment_note
            ?: 'No additional instructions provided.';


        /*
        |--------------------------------------------------------------------------
        | Assigned By
        |--------------------------------------------------------------------------
        */

        $assignedByName =
            $assignedBy->name
            ?? 'Editor-in-Chief';


        /*
        |--------------------------------------------------------------------------
        | Build Email
        |--------------------------------------------------------------------------
        */

        return (new MailMessage)

            ->subject(
                'Handling Editor Invitation - ' .
                $manuscriptNumber
            )

            ->greeting(
                'Dear ' . $notifiable->name . ','
            )

            ->line(
                'You have been invited to serve as the Handling Editor for a manuscript submitted to the BMRC Bulletin.'
            )

            ->line(
                'Manuscript ID: ' .
                $manuscriptNumber
            )

            ->line(
                'Title: ' .
                $title
            )

            ->line(
                'Assignment Round: ' .
                $assignment->assignment_round
            )

            ->line(
                'Assigned By: ' .
                $assignedByName
            )

            ->line(
                'Assessment Deadline: ' .
                $dueDate
            )

            ->line(
                'Assignment Instructions: ' .
                $assignmentNote
            )

            ->action(
                'View Assignment',
                route(
                    'handling-editor.assignments.show',
                    $assignment->id
                )
            )

            ->line(
                'Please log in to the BMRC Journal system to review the manuscript assignment and accept or decline the invitation.'
            )

            ->line(
                'If you accept the assignment, you will be able to proceed with the editorial assessment of the manuscript.'
            )

            ->salutation(
                "Regards,\n" .
                "Editorial Office\n" .
                "BMRC Bulletin\n" .
                "Bangladesh Medical Research Council (BMRC)"
            );
    }


    /**
     * Get the array representation.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'editor_assignment_id' => $this->assignment->id,
            'manuscript_id'        => $this->assignment->manuscript_id,
            'status'               => $this->assignment->status,
        ];
    }
}