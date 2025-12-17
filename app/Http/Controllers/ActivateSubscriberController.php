<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ActivateSubscriberController extends Controller
{
    public function dashboard()
    {
        $totalSubscribers = Subscriber::count();
        $todaySubscribers = Subscriber::whereDate('created_at', today())->count();
        $weekSubscribers = Subscriber::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthSubscribers = Subscriber::whereMonth('created_at', now()->month)->count();
        $recentSubscribers = Subscriber::latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalSubscribers',
            'todaySubscribers', 
            'weekSubscribers',
            'monthSubscribers',
            'recentSubscribers'
        ));
    }

    public function activateSubscriber()
    {
        $subscribers = Subscriber::latest()->get();
        return view('admin.activate-subscriber', compact('subscribers'));
    }

    public function index()
    {
        $subscribers = Subscriber::latest()->get();
        return response()->json($subscribers);
    }

    public function show($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        return view('admin.subscriber-details', compact('subscriber'));
    }

    private function verifySubscriberWithAPI($data)
    {
        $apiUrl = $this->apiUrl('ActivateSubscriberWithAddress');
        $clientId = '65';
        $clientApiKey = 'BC456511-FC81-4E1F-BC93-E7C5C7145CEF';
        $transactionId = 'txn_' . uniqid() . '_' . time();
        
        $payload = [
            'sim' => $data['sim'],
            'zip' => $data['zip'],
            'plan_soc' => $data['plan_soc'],
            'imei' => $data['imei'],
            'label' => $data['label'],
            'e911AddressStreet1' => $data['e911AddressStreet1'],
            'e911AddressStreet2' => $data['e911AddressStreet2'] ?? '',
            'e911AddressCity' => $data['e911AddressCity'],
            'e911AddressState' => $data['e911AddressState'],
            'e911AddressZip' => $data['e911AddressZip'],
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => '*/*',
                'client-id' => $clientId,
                'client-api-key' => $clientApiKey,
                'transaction-id' => $transactionId,
            ])->timeout(30)->post($apiUrl, $payload);

            $responseData = $response->json();
            if ($response->successful()) {
                if (isset($responseData['data']['error'])) {
                    return [
                        'success' => false,
                        'message' => $responseData['data']['error']['error'] ?? $responseData['data']['error']['message'] ?? 'API verification failed',
                        'error_code' => $responseData['data']['error']['code'] ?? null,
                        'status_code' => $responseData['data']['error']['statusCode'] ?? 0,
                    ];
                }

                return [
                    'success' => true,
                    'transaction_id' => $responseData['transactionId'] ?? $transactionId,
                    'data' => $responseData['data'] ?? [],
                    'status' => $responseData['data']['status'] ?? 'success',
                    'msisdn' => $responseData['data']['msisdn'] ?? null,
                    'iccid' => $responseData['data']['iccid'] ?? null,
                    'account_id' => $responseData['data']['accountId'] ?? $responseData['data']['account_id'] ?? null,
                ];
            }
            return [
                'success' => false,
                'message' => $responseData['message'] ?? $responseData['data']['error']['message'] ?? 'API request failed',
                'status_code' => $response->status(),
            ];

        } catch (\Exception $e) {
            \Log::error('API Connection Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'API connection failed: ' . $e->getMessage(),
            ];
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'sim' => 'required|string|max:255',
                'zip' => 'required|string|max:255',
                'plan_soc' => 'required|string|max:255',
                'imei' => 'nullable|string|max:255',
                'label' => 'required|string|max:255',
                'e911AddressStreet1' => 'required|string|max:255',
                'e911AddressStreet2' => 'nullable|string|max:255',
                'e911AddressCity' => 'required|string|max:255',
                'e911AddressState' => 'required|string|max:255',
                'e911AddressZip' => 'required|string|max:255',
            ]);

            $apiVerification = $this->verifySubscriberWithAPI($validated);
            
            if (!$apiVerification['success']) {
                $errorMessage = $apiVerification['message'] ?? 'API verification failed';
                
                return redirect()->route('admin.activate-subscriber')
                    ->withErrors(['api' => $errorMessage])
                    ->withInput();
            }

            $subscriber = Subscriber::create([
                'sim' => $validated['sim'],
                'zip' => $validated['zip'],
                'plan_soc' => $validated['plan_soc'],
                'imei' => $validated['imei'],
                'label' => $validated['label'],
                'e911_address_street1' => $validated['e911AddressStreet1'],
                'e911_address_street2' => $validated['e911AddressStreet2'] ?? null,
                'e911_address_city' => $validated['e911AddressCity'],
                'e911_address_state' => $validated['e911AddressState'],
                'e911_address_zip' => $validated['e911AddressZip'],
                'transaction_id' => $apiVerification['transaction_id'] ?? null,
                'msisdn' => $apiVerification['msisdn'] ?? null,
                'iccid' => $apiVerification['iccid'] ?? null,
                'account_id' => $apiVerification['account_id'] ?? null,
                'api_status' => $apiVerification['status'] ?? null,
            ]);

            return redirect()->route('admin.activate-subscriber')
                ->with('success', 'Subscriber activated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.activate-subscriber')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('admin.activate-subscriber')
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Download CSV template for bulk import
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers_import_template.csv"',
        ];

        $columns = [
            'Customer Name',
            'SIM Number (ICCID)',
            'IMEI',
            'Rate Plan',
            'E911 Street Address',
            'E911 City',
            'E911 State',
            'E911 ZIP',
            'Customer Email'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Add sample row
            fputcsv($file, [
                'John Doe',
                '89012345678901234567',
                '123456789012345',
                'PLAN001',
                '123 Main Street',
                'New York',
                'NY',
                '10001',
                'john@example.com'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import subscribers from CSV/Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx,xls|max:20480',
            'skip_duplicates' => 'nullable|boolean',
            'validate_only' => 'nullable|boolean',
        ]);

        $file = $request->file('import_file');
        $skipDuplicates = $request->boolean('skip_duplicates', true);
        $validateOnly = $request->boolean('validate_only', false);

        try {
            $data = $this->parseImportFile($file);
            
            if (empty($data)) {
                return redirect()->route('admin.activate-subscriber')
                    ->withErrors(['import' => 'The uploaded file is empty or has no valid data rows.']);
            }

            $results = [
                'success' => 0,
                'failed' => 0,
                'skipped' => 0,
                'errors' => [],
            ];

            foreach ($data as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2; // +2 because row 1 is headers, and index is 0-based
                
                // Validate required fields
                $missingFields = $this->validateImportRow($row);
                if (!empty($missingFields)) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNumber}: Missing required fields - " . implode(', ', $missingFields);
                    continue;
                }

                // Check for duplicates
                if ($skipDuplicates && Subscriber::where('sim', $row['sim'])->exists()) {
                    $results['skipped']++;
                    continue;
                }

                if ($validateOnly) {
                    $results['success']++;
                    continue;
                }

                // Prepare data for API
                $subscriberData = [
                    'sim' => $row['sim'],
                    'zip' => $row['zip'],
                    'plan_soc' => $row['plan_soc'],
                    'imei' => $row['imei'],
                    'label' => $row['label'],
                    'e911AddressStreet1' => $row['e911_address_street1'],
                    'e911AddressStreet2' => $row['e911_address_street2'] ?? '',
                    'e911AddressCity' => $row['e911_address_city'],
                    'e911AddressState' => $row['e911_address_state'],
                    'e911AddressZip' => $row['e911_address_zip'],
                ];

                // Verify with API
                $apiVerification = $this->verifySubscriberWithAPI($subscriberData);

                if (!$apiVerification['success']) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNumber}: " . ($apiVerification['message'] ?? 'API verification failed');
                    continue;
                }

                // Create subscriber
                Subscriber::create([
                    'sim' => $subscriberData['sim'],
                    'zip' => $subscriberData['zip'],
                    'plan_soc' => $subscriberData['plan_soc'],
                    'imei' => $subscriberData['imei'],
                    'label' => $subscriberData['label'],
                    'e911_address_street1' => $subscriberData['e911AddressStreet1'],
                    'e911_address_street2' => $subscriberData['e911AddressStreet2'] ?? null,
                    'e911_address_city' => $subscriberData['e911AddressCity'],
                    'e911_address_state' => $subscriberData['e911AddressState'],
                    'e911_address_zip' => $subscriberData['e911AddressZip'],
                    'transaction_id' => $apiVerification['transaction_id'] ?? null,
                    'msisdn' => $apiVerification['msisdn'] ?? null,
                    'iccid' => $apiVerification['iccid'] ?? null,
                    'account_id' => $apiVerification['account_id'] ?? null,
                    'api_status' => $apiVerification['status'] ?? null,
                ]);

                $results['success']++;
            }

            // Build result message
            $message = $validateOnly 
                ? "Validation complete: {$results['success']} valid rows found."
                : "Import complete: {$results['success']} subscribers imported successfully.";
            
            if ($results['skipped'] > 0) {
                $message .= " {$results['skipped']} duplicates skipped.";
            }
            
            if ($results['failed'] > 0) {
                $message .= " {$results['failed']} rows failed.";
            }

            if (!empty($results['errors'])) {
                return redirect()->route('admin.activate-subscriber')
                    ->with('success', $message)
                    ->withErrors(['import_errors' => $results['errors']]);
            }

            return redirect()->route('admin.activate-subscriber')
                ->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Import Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.activate-subscriber')
                ->withErrors(['import' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Parse the import file (CSV or Excel)
     */
    private function parseImportFile($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $data = [];

        // Column mapping: CSV header => internal field name
        $columnMapping = [
            'customer name' => 'label',
            'sim number (iccid)' => 'sim',
            'imei' => 'imei',
            'rate plan' => 'plan_soc',
            'e911 street address' => 'e911_address_street1',
            'e911 city' => 'e911_address_city',
            'e911 state' => 'e911_address_state',
            'e911 zip' => 'e911_address_zip',
            'customer email' => 'email',
        ];

        if ($extension === 'csv') {
            $handle = fopen($file->getRealPath(), 'r');
            $headers = null;
            
            while (($row = fgetcsv($handle)) !== false) {
                if ($headers === null) {
                    // Map CSV headers to internal field names
                    $headers = [];
                    foreach ($row as $header) {
                        $normalizedHeader = strtolower(trim($header));
                        $headers[] = $columnMapping[$normalizedHeader] ?? $normalizedHeader;
                    }
                    continue;
                }
                
                if (count($row) === count($headers)) {
                    $rowData = array_combine($headers, array_map('trim', $row));
                    // Extract ZIP from E911 ZIP for the main zip field
                    $rowData['zip'] = $rowData['e911_address_zip'] ?? '';
                    $data[] = $rowData;
                }
            }
            
            fclose($handle);
        } else {
            // For Excel files, you'll need to install maatwebsite/excel package
            // composer require maatwebsite/excel
            throw new \Exception('Excel file support requires the maatwebsite/excel package. Please use CSV format or install the package.');
        }

        return $data;
    }

    /**
     * Validate import row data
     */
    private function validateImportRow($row)
    {
        $required = [
            'sim' => 'SIM Number (ICCID)',
            'plan_soc' => 'Rate Plan',
            'label' => 'Customer Name',
            'e911_address_street1' => 'E911 Street Address',
            'e911_address_city' => 'E911 City',
            'e911_address_state' => 'E911 State',
            'e911_address_zip' => 'E911 ZIP'
        ];

        $missing = [];
        foreach ($required as $field => $displayName) {
            if (empty($row[$field] ?? null)) {
                $missing[] = $displayName;
            }
        }

        return $missing;
    }
}
