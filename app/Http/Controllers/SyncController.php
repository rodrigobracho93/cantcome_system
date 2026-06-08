<?php

namespace App\Http\Controllers;

use App\Models\SyncLog;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncController extends Controller
{
    public function sync()
    {
        $results = ['synced' => 0, 'failed' => 0, 'errors' => []];

        $pendingLogs = SyncLog::where('synced', false)->orderBy('created_at')->get();

        foreach ($pendingLogs as $log) {
            try {
                $response = Http::withHeaders(['X-Auth-Token' => config('sync.token')])
                    ->post(config('sync.server_url') . '/api/sync/receive', [
                        'table' => $log->table_name,
                        'action' => $log->action,
                        'record_id' => $log->record_id,
                        'payload' => $log->payload,
                    ]);

                if ($response->successful()) {
                    $log->update(['synced' => true, 'synced_at' => now()]);
                    $results['synced']++;
                } else {
                    throw new \Exception($response->body());
                }
            } catch (\Exception $e) {
                $log->update(['error_message' => $e->getMessage()]);
                $results['failed']++;
                $results['errors'][] = $e->getMessage();
            }
        }

        return response()->json($results);
    }

    public function pushUnsynced()
    {
        $sales = Sale::where('synced', false)->get();
        $count = 0;

        foreach ($sales as $sale) {
            try {
                $data = $sale->toArray();
                $data['items'] = $sale->items()->get()->toArray();

                $response = Http::withHeaders(['X-Auth-Token' => config('sync.token')])
                    ->post(config('sync.server_url') . '/api/sync/sale', $data);

                if ($response->successful()) {
                    $sale->update(['synced' => true, 'synced_at' => now()]);
                    $count++;
                }
            } catch (\Exception $e) {
                SyncLog::create([
                    'table_name' => 'sales',
                    'record_id' => $sale->id,
                    'action' => 'create',
                    'payload' => json_encode($sale->toArray()),
                    'synced' => false,
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        return response()->json(['synced' => $count]);
    }

    public function receiveSync(Request $request)
    {
        $token = $request->header('X-Auth-Token');
        if ($token !== config('sync.token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $table = $request->input('table');
        $action = $request->input('action');
        $recordId = $request->input('record_id');
        $payload = $request->input('payload');

        DB::transaction(function () use ($table, $action, $recordId, $payload) {
            $model = $this->getModel($table);

            if ($action === 'delete') {
                $model::find($recordId)?->delete();
            } else {
                $model::updateOrCreate(['id' => $recordId], $payload);
            }
        });

        return response()->json(['success' => true]);
    }

    public function receiveSale(Request $request)
    {
        $token = $request->header('X-Auth-Token');
        if ($token !== config('sync.token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->all();

        DB::transaction(function () use ($data) {
            $sale = Sale::updateOrCreate(
                ['id' => $data['id'] ?? null],
                collect($data)->except(['items', 'id'])->toArray()
            );

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    SaleItem::updateOrCreate(
                        ['id' => $item['id'] ?? null],
                        $item
                    );
                }
            }
        });

        return response()->json(['success' => true]);
    }

    private function getModel(string $table): string
    {
        return match ($table) {
            'products' => Product::class,
            'categories' => Category::class,
            'customers' => Customer::class,
            'sales' => Sale::class,
            default => throw new \InvalidArgumentException("Unknown table: $table"),
        };
    }
}
