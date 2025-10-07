<?php

namespace App\Http\Controllers\backend\parametre;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = Activity::with(['causer', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get();
            // dd($logs->toArray());

        return view('backend.pages.activity-logs.index', compact('logs'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        try {
            $log = Activity::findOrFail($id);
            $log->delete();

            return response()->json([
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }

    /**
     * Supprimer tous les logs
     */
    public function clearAll()
    {
        try {
            Activity::truncate();
            return response()->json(['success' => true, 'message' => 'Tous les logs ont été supprimés']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }
}
