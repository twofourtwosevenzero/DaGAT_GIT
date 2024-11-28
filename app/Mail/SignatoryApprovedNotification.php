<?php

namespace App\Mail;

use App\Models\Document;
use App\Models\Signatory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignatoryApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $signatory;
    public $nextSignatory;

    /**
     * Create a new message instance.
     */
    public function __construct(Document $document, Signatory $signatory, $nextSignatory = null)
    {
        $this->document = $document;
        $this->signatory = $signatory;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Document Approved by ' . $this->signatory->office->Office_Name)
                    ->view('emails.signatory_approved_notification');
    }
}
