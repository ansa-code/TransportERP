<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;

    public string $messageText;

    public string $pdfContent;

    public string $pdfFileName;

    /**
     * Create a new message instance.
     */
    public function __construct(
        Invoice $invoice,
        string $messageText = '',
        string $pdfContent = '',
        string $pdfFileName = 'invoice.pdf'
    ) {
        $this->invoice = $invoice;

        $this->messageText = $messageText;

        $this->pdfContent = $pdfContent;

        $this->pdfFileName = $pdfFileName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice ' . (
                $this->invoice->invoice_no
                ?? $this->invoice->invoice_number
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        if ($this->pdfContent === '') {
            return [];
        }

        return [
            Attachment::fromData(
                fn () => $this->pdfContent,
                $this->pdfFileName
            )->withMime('application/pdf'),
        ];
    }
}