<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Document;
use App\Models\User;
use App\Models\DocumentType;
use App\Models\Status;
use App\Models\Signatory;
use App\Models\QuickResponseCode;
use App\Models\Office;
use Carbon\Carbon;

class DocumentsSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $documentTypes = DocumentType::all();
        $statuses = Status::all();
        $offices = Office::all();

        if ($users->isEmpty() || $documentTypes->isEmpty() || $statuses->isEmpty() || $offices->isEmpty()) {
            $this->command->error('Ensure Users, Document Types, Statuses, and Offices tables are populated before running this seeder.');
            return;
        }

        $baseUrl = 'http://192.168.254.107:8000'; // Update your server's IP and Port

        for ($i = 1; $i <= 100; $i++) {
            $status = $statuses->random();
            $dateCreated = Carbon::now()->subDays(rand(10, 90));
            $dateApproved = $status->Status_Name === 'Approved' ? $dateCreated->copy()->addDays(rand(1, 15)) : null;

            $document = Document::create([
                'user_id' => $users->random()->id,
                'DT_ID' => $documentTypes->random()->id,
                'Status_ID' => $status->id,
                'Description' => "Document {$i}",
                'Date_Created' => $dateCreated,
                'Date_Approved' => $dateApproved,
                'Document_File' => "documents/sample_doc_{$i}.pdf",
            ]);

            // Generate QR Code
            $qrCodeUrl = "{$baseUrl}/qrcode/scan/{$document->id}";
            $qrCode = QrCode::format('svg')->size(200)->generate($qrCodeUrl);
            $qrCodePath = "qrcodes/{$document->id}.svg";
            Storage::disk('public')->put($qrCodePath, $qrCode);

            QuickResponseCode::create([
                'Docu_ID' => $document->id,
                'QR_Image' => $qrCodePath,
                'Date_Created' => Carbon::now(),
                'Usage_Count' => 0,
            ]);

            // Assign Signatories
            foreach ($offices->random(rand(2, 5)) as $office) {
                $receivedDate = $dateCreated->copy()->addDays(rand(1, 7));
                $signedDate = $status->Status_Name === 'Approved' ? $receivedDate->copy()->addDays(rand(1, 5)) : null;

                Signatory::create([
                    'QRC_ID' => $document->id,
                    'Office_ID' => $office->id,
                    'Status_ID' => $status->id,
                    'Received_Date' => $receivedDate,
                    'Signed_Date' => $signedDate,
                ]);
            }

            $this->command->info("Created Document {$i} with QR Code and randomized signatories.");
        }
    }
}
