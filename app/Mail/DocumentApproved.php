<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Document;

class DocumentApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $lastApprovedOffice;

    /**
     * Create a new message instance.
     *
     * @param Document $document
     * @param Office $lastApprovedOffice
     */
    public function __construct(Document $document, $lastApprovedOffice)
    {
        $this->document = $document;
        $this->lastApprovedOffice = $lastApprovedOffice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Passing document title, approval date, and last approved office to the view
        return $this->view('emails.document_approved')
                    ->subject('Document Fully Approved')
                    ->with([
                        'documentTitle' => $this->document->Description, // Assuming 'Description' is the title
                        'approvalDate' => now(), // You can replace this with the actual approval date from the document
                        'lastApprovedOffice' => $this->lastApprovedOffice,
                    ]);
    }
}
