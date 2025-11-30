<?php

namespace Hemel\UniversalCrud\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class UniversalCrudController extends Controller
{
    /**
     * Table allowed কিনা, আর table exist করে কিনা check.
     */
    protected function ensureTableAllowed(string $table): void
    {
        $allowed = config('universal-crud.allowed_tables', ['*']);

        if ($allowed !== ['*'] && ! in_array($table, $allowed, true)) {
            abort(403, 'Table not allowed.');
        }

        if (! Schema::hasTable($table)) {
            abort(404, 'Table not found.');
        }
    }

    /**
     * সব allowed table list.
     */
    public function tables()
    {
        $allowed = config('universal-crud.allowed_tables', ['*']);

        // যদি whitelist set থাকে, direct ওটাই ফেরত দেই
        if ($allowed !== ['*']) {
            return response()->json(array_values($allowed));
        }

        // MySQL specific: SHOW TABLES
        $tables = collect(DB::select('SHOW TABLES'))
            ->map(function ($row) {
                return collect((array) $row)->first();
            })
            ->values();

        return response()->json($tables);
    }

    /**
     * নির্দিষ্ট table এর visible columns list.
     */
    public function columns(string $table)
    {
        $this->ensureTableAllowed($table);

        $columns = Schema::getColumnListing($table);
        $hidden = config('universal-crud.hidden_columns', []);

        $columns = array_values(array_diff($columns, $hidden));

        return response()->json($columns);
    }

    /**
     * Index / list (simple pagination সহ).
     */
    public function index(Request $request, string $table)
    {
        $this->ensureTableAllowed($table);

        $query = DB::table($table);

        // future এ filter, sort এখানে add করতে পারো

        $perPage = (int) $request->input('per_page', 15);
        if ($perPage <= 0) {
            $perPage = 15;
        }
        if ($perPage > 100) {
            $perPage = 100; // hard limit
        }

        $data = $query->paginate($perPage);

        return response()->json($data);
    }

    /**
     * Single record show.
     */
    public function show(string $table, $id)
    {
        $this->ensureTableAllowed($table);

        $row = DB::table($table)->where('id', $id)->first();

        if (! $row) {
            abort(404, 'Record not found.');
        }

        return response()->json($row);
    }

    /**
     * Create (insert).
     */
    public function store(Request $request, string $table)
    {
        $this->ensureTableAllowed($table);

        $data = $this->filterInputForTable($table, $request);

        $id = DB::table($table)->insertGetId($data);

        $row = DB::table($table)->where('id', $id)->first();

        return response()->json($row, 201);
    }

    /**
     * Update.
     */
    public function update(Request $request, string $table, $id)
    {
        $this->ensureTableAllowed($table);

        $data = $this->filterInputForTable($table, $request);

        if (empty($data)) {
            return response()->json([
                'message' => 'Nothing to update.',
            ], 422);
        }

        $updated = DB::table($table)->where('id', $id)->update($data);

        if (! $updated) {
            abort(404, 'Record not found or not updated.');
        }

        $row = DB::table($table)->where('id', $id)->first();

        return response()->json($row);
    }

    /**
     * Delete.
     */
    public function destroy(string $table, $id)
    {
        $this->ensureTableAllowed($table);

        $deleted = DB::table($table)->where('id', $id)->delete();

        if (! $deleted) {
            abort(404, 'Record not found.');
        }

        return response()->json(['deleted' => true]);
    }

    /**
     * Table এর column অনুযায়ী input filter করা + file/image upload handle করা।
     */
    protected function filterInputForTable(string $table, Request $request): array
    {
        $columns = Schema::getColumnListing($table);
        $hidden  = config('universal-crud.hidden_columns', []);

        // hidden + id বাদ দিচ্ছি
        $columns = array_diff($columns, $hidden);

        $data = [];

        foreach ($columns as $column) {
            if ($column === 'id') {
                continue;
            }

            // যদি column নামের সাথে মিলিয়ে file পাঠানো হয়
            if ($request->hasFile($column)) {
                $file = $request->file($column);

                if ($file && $file->isValid()) {
                    $disk     = config('universal-crud.upload_disk', 'public');
                    $basePath = trim(config('universal-crud.upload_base_path', 'uploads'), '/');

                    // client ইচ্ছা করলে sub-path দিতে পারবে: ?_upload_path=users/avatars
                    $subPath = trim((string) $request->input('_upload_path', ''), '/');

                    $directory = $basePath;
                    if ($subPath !== '') {
                        $directory .= '/'.$subPath;
                    }

                    $path = $file->store($directory, $disk);

                    $data[$column] = $path;
                }

                // file থাকলে এই iteration শেষ
                continue;
            }

            // normal scalar input
            if ($request->exists($column)) {
                $data[$column] = $request->input($column);
            }
        }

        // timestamps handle
        if (in_array('updated_at', $columns, true) && ! array_key_exists('updated_at', $data)) {
            $data['updated_at'] = now();
        }

        if (in_array('created_at', $columns, true) && ! array_key_exists('created_at', $data)) {
            // insert এর সময়ই শুধু ব্যবহার হবে, update এ override হবে না
            if (! $request->routeIs('*update*')) {
                $data['created_at'] = now();
            }
        }

        return $data;
    }
}
