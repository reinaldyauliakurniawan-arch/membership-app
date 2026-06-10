<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Support\Invoices\InvoiceDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Invoice issued email (member-facing).
 *
 * Sends an invoice summary and attaches the invoice PDF.
 */
class InvoiceIssuedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  non-empty-string  $clubName
     */
    public function __construct(
        public readonly Invoice $invoice,
        public readonly string $subjectLine,
        public readonly string $clubName,
        public readonly string $clubEmail,
        public readonly string $clubContact,
        public readonly string $memberName,
        public readonly ?string $note,
        public readonly string $pdfBytes,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoices.issued',
            with: [
                'invoice' => $this->invoice,
                'clubName' => $this->clubName,
                'clubEmail' => $this->clubEmail,
                'clubContact' => $this->clubContact,
                'memberName' => $this->memberName,
                'note' => $this->note,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfBytes, InvoiceDocument::pdfFilename($this->invoice))
                ->withMime('application/pdf'),
        ];
    }
}
