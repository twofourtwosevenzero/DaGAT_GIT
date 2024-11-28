<?php

namespace App\Mail;

use App\Models\Document;
use App\Models\Office;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentRevisionRequested extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $office;
    public $revisionType;
    public $revisionReason;

    /**
     * Create a new message instance.
     */
    public function __construct(Document $document, Office $office, $revisionType, $revisionReason)
    {
        $this->document = $document;
        $this->office = $office;
        $this->revisionType = $revisionType;
        $this->revisionReason = $revisionReason;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Revision Requested for Document: ' . $this->document->Description)
                    ->view('emails.document_revision_requested');
    }
}
