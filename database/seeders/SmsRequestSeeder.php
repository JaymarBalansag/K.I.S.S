<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmsRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $messages = [
            [
                'phone_number' => '09171234567',
                'message' => 'KISS: Your marriage license application has been received. Please wait for the next update from the Municipal Civil Registrar.',
            ],
            [
                'phone_number' => '09182345678',
                'message' => 'KISS: Your appointment schedule is ready. Please visit the office on your selected date and bring your valid IDs.',
            ],
            [
                'phone_number' => '09193456789',
                'message' => 'KISS: Reminder - submit the missing requirements for your application to avoid delays in processing.',
            ],
            [
                'phone_number' => '09914567890',
                'message' => 'KISS: Your document request is being reviewed. We will send another SMS once it is approved.',
            ],
            [
                'phone_number' => '09925678901',
                'message' => 'KISS: Follow-up notice - please check your application details in the office if any information needs correction.',
            ],
        ];

        foreach ($messages as $message) {
            DB::table('sms_requests')->updateOrInsert(
                [
                    'phone_number' => $message['phone_number'],
                    'message' => $message['message'],
                ],
                [
                    'status' => 'pending',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
