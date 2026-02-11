<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\RegistrationDirectly;
use Carbon\Carbon;

// Khởi tạo Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TẠO DỮ LIỆU TEST ===\n\n";

// Tạo record test với ngày 2025-02-12
$testRecord = new RegistrationDirectly();
$testRecord->name = 'Test User - Feb 12';
$testRecord->id_card = '123456789';
$testRecord->license_plate = 'TEST-123';
$testRecord->start_date = '2025-02-12 10:00:00';
$testRecord->expected_arrival_time = '2025-02-12 08:00:00';
$testRecord->status = 'waiting_entry';
$testRecord->save();

echo "✅ Đã tạo record test với ID: {$testRecord->id}\n";
echo "   Start Date: {$testRecord->start_date}\n";
echo "   Expected Arrival: {$testRecord->expected_arrival_time}\n\n";

// Test filter với ngày này
$filterDate = '2025-02-12';
$results = RegistrationDirectly::where(function($query) use ($filterDate) {
    $date = Carbon::parse($filterDate, 'Asia/Ho_Chi_Minh');
    return $query->where(function($subQuery) use ($date) {
        $subQuery->whereDate('start_date', $date)
            ->orWhereDate('expected_arrival_time', $date);
    });
})->get();

echo "🔍 Test filter với ngày {$filterDate}: {$results->count()} bản ghi\n";

foreach($results as $record) {
    echo "   ID: {$record->id}, Name: {$record->name}\n";
}

echo "\n=== HOÀN THÀNH ===\n";
echo "Bây giờ bạn có thể test URL: http://192.168.1.70:8005/registration-directlies?filters[registration_filters][filter_start_date]=2025-02-12\n";
