<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\Signatory;

class ActivityLogsSeeder extends Seeder
{
    public function run()
    {
        $documents = Document::all();

        if ($documents->isEmpty()) {
            $this->command->error('Ensure the Documents table is populated before running this seeder.');
            return;
        }

        foreach ($documents as $document) {
            // Log document creation
            ActivityLog::create([
                'Docu_ID' => $document->id,
                'Sign_ID' => null,
                'action' => 'Created',
                'Timestamp' => $document->Date_Created,
                'user_id' => $document->user_id,
                'reason' => null,
                'requested_by' => null,
            ]);

            // Log actions for each signatory
            $signatories = Signatory::where('QRC_ID', $document->id)->get();
            foreach ($signatories as $signatory) {
                if ($signatory->Received_Date) {
                    ActivityLog::create([
                        'Docu_ID' => $document->id,
                        'Sign_ID' => $signatory->id,
                        'action' => 'Received',
                        'Timestamp' => $signatory->Received_Date,
                        'user_id' => null,
                        'reason' => null,
                        'requested_by' => $signatory->Office_ID,
                    ]);
                }

                if ($signatory->Signed_Date) {
                    ActivityLog::create([
                        'Docu_ID' => $document->id,
                        'Sign_ID' => $signatory->id,
                        'action' => 'Approved',
                        'Timestamp' => $signatory->Signed_Date,
                        'user_id' => null,
                        'reason' => null,
                        'requested_by' => $signatory->Office_ID,
                    ]);
                }
            }

            $this->command->info("Logged activities for Document ID {$document->id}.");
        }
    }
}
