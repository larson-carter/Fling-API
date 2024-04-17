<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NonConfirmedMac;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DeviceMacController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate the request data if needed
            // $request->validate([
            //     'device_macs' => 'required|array',
            //     'device_macs.*' => 'required|string|uuid', // Ensure each MAC address is a valid UUID
            // ]);

            // Your controller logic here
            foreach ($request->all() as $device_mac) {
                // Ensure each MAC address is a valid UUID
                if (!Str::isUuid($device_mac)) {
                    throw new \InvalidArgumentException("Invalid UUID format: $device_mac");
                }

                // Create NonConfirmedMac record with device MAC address
                NonConfirmedMac::create([
                    'device_mac' => $device_mac,
                ]);
            }

            // Log debug information
            Log::debug('Request data:', $request->all());
            return response()->json(['message' => 'Device MAC addresses have been successfully stored.'], 201);

        } catch (\Exception $e) {
            // Log error
            Log::error('Error occurred:', ['exception' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
