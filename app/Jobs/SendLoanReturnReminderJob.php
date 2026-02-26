<?php

namespace App\Jobs;

use App\Models\Loan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendLoanReturnReminderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Loan $loan) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $loan = $this->loan->loadMissing('student');

        if (! $loan->student?->whatsapp_number) {
            Log::warning('Reminder WA tidak dikirim: nomor WhatsApp siswa kosong.', [
                'loan_id' => $loan->id,
                'student_id' => $loan->student_id,
            ]);

            return;
        }

        // Placeholder integrasi WA gateway.
        Log::info('Reminder pengembalian berhasil diproses (simulasi).', [
            'loan_id' => $loan->id,
            'student_id' => $loan->student_id,
            'student_name' => $loan->student?->name,
            'whatsapp_number' => $loan->student?->whatsapp_number,
            'due_at' => $loan->due_at?->toDateTimeString(),
        ]);
    }
}
