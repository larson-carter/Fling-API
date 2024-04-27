<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NonConfirmedMac;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\ConfirmedMac;

class DeviceMacController extends Controller
{

    public function store(Request $request)
    {
        try {
            // Your existing controller logic here
            foreach ($request->all() as $device_mac) {
                // Ensure each MAC address is a valid UUID
                if (!Str::isUuid($device_mac)) {
                    throw new \InvalidArgumentException("Invalid UUID format: $device_mac");
                }

                // Check if the MAC address exists in the confirmed_mac table
                $confirmed_mac = ConfirmedMac::where('device_mac', $device_mac)->first();

//                if ($confirmed_mac) {
//                    // If a match is found, return the matched MAC address with response code
//                    return response()->json(['message' => 'Matching MAC address found in confirmed_mac table.', 'matched_mac' => $device_mac], 409);
//                }

                if ($confirmed_mac) {
                    // If a match is found, return the matched MAC address and common_device_name with response code
                    return response()->json([
                        'message' => 'Matching MAC address found in confirmed_mac table.',
                        'matched_mac' => $device_mac,
                        'common_device_name' => $confirmed_mac->common_device_name,
                    ], 409);
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
