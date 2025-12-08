<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Log;

$u = User::find(21);
$extEmail = 'saputrailham372@gmail.com';
$other = User::where('email', $extEmail)->where('id', '!=', $u->id)->first();
if ($other) {
    echo "Conflict with user id {$other->id}\n";
} else {
    $u->email = $extEmail;
    $u->save();
    echo "Updated OK\n";
}
