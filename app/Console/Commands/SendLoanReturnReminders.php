<?php

namespace App\Console\Commands;

use App\Jobs\SendLoanReturnReminderJob;
use App\Models\Loan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendLoanReturnReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:send-return-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim reminder WhatsApp untuk pinjaman yang belum kembali melewati due time';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $totalDispatched = 0;

        Loan::query()
            ->with('student')
            ->where('status', 'borrowed')
            ->whereNull('wa_notified_at')
            ->where('due_at', '<=', now())
            ->chunkById(100, function ($loans) use (&$totalDispatched): void {
                foreach ($loans as $loan) {
                    DB::transaction(function () use ($loan, &$totalDispatched): void {
                        $lockedLoan = Loan::query()
                            ->whereKey($loan->id)
                            ->lockForUpdate()
                            ->first();

                        if (! $lockedLoan || $lockedLoan->status !== 'borrowed' || $lockedLoan->wa_notified_at) {
                            return;
                        }

                        $lockedLoan->update([
                            'status' => 'overdue',
                            'wa_notified_at' => now(),
                        ]);

                        SendLoanReturnReminderJob::dispatch($lockedLoan);
                        $totalDispatched++;
                    });
                }
            });

        $this->info("Total reminder diproses: {$totalDispatched}");

        return self::SUCCESS;
    }
}
