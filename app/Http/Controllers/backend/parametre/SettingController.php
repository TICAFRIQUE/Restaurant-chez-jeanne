<?php

namespace App\Http\Controllers\backend\parametre;

use App\Models\Setting;
use App\Models\Optimize;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    //get infos of setting
    public function index()
    {
        $data_setting = Setting::first();

        //get status mode maintenance
        $data_maintenance = Maintenance::latest()->select('type')->first();

        // recuperer la liste des sauvegardes du projet
        $appName = config('app.name');
        $backup = Storage::disk('local')->files('' . $appName . '/');


        // dd($backup_db);
        return view('backend.pages.setting.index', compact('data_setting', 'data_maintenance', 'backup'));
    }

    // Télécharger un fichier de sauvegarde
    public function downloadBackup($file)
    {
        // $path = "Restaurant/" . $file;

        // if (Storage::disk('local')->exists($path)) {
        //     return Storage::disk('local')->download($path);
        // }
        $appName = config('app.name');
        $path = storage_path("app/" . $appName . "/" . $file);

        if (file_exists($path)) {
            return response()->download($path);
        }

        Alert::error('Fichier non trouvé.', 'Error Message');

        return back();

        // return redirect()->back()->with('error', 'Fichier non trouvé.');
    }


    public function store(Request $request)
    {
        //request validation................
        // dd($request->all());

        //verify if data exist
        $data_exist = Setting::with('media')->get();


        if (count($data_exist) > 0) {
            // dd($request->all());

            $data_exist_ = Setting::with('media')->first();
            $media = $data_exist_->media;
            // dd(count($data_exist_->media));


            //insert data
            //update data if record exist
            $data_setting = tap(Setting::find($data_exist_['id']))->update([
                'facebook_link' => $request['facebook_link'],
                'instagram_link' => $request['instagram_link'],
                'twitter_link' => $request['twitter_link'],
                'linkedin_link' => $request['linkedin_link'],
                'tiktok_link' => $request['tiktok_link'],

                //infos application
                'projet_title' => $request['projet_title'],
                'projet_description' => $request['projet_description'],
                'phone1' => $request['phone1'],
                'phone2' => $request['phone2'],
                'phone3' => $request['phone3'],

                'email1' => $request['email1'],
                'email2' => $request['email2'],

                'localisation' => $request['localisation'],
                'google_maps' => $request['google_maps'],
                'siege_social' => $request['siege_social'],

                //security
                // 'mode_maintenance'=>'',
            ]);

            //insert image logo

            if ($request->has('cover') && count($media) > 0) {
                $data_setting->clearMediaCollection('cover');
                $data_setting->addMediaFromRequest('cover')->toMediaCollection('cover');
            } elseif ($request->has('cover')) {
                $data_setting->addMediaFromRequest('cover')->toMediaCollection('cover');
            }

            if ($request->has('logo_header') && count($media) > 0) {
                $data_setting->clearMediaCollection('logo_header');
                $data_setting->addMediaFromRequest('logo_header')->toMediaCollection('logo_header');
            } elseif ($request->has('logo_header')) {
                $data_setting->addMediaFromRequest('logo_header')->toMediaCollection('logo_header');
            }


            if ($request->has('logo_footer') && count($media) > 0) {
                $data_setting->clearMediaCollection('logo_footer');
                $data_setting->addMediaFromRequest('logo_footer')->toMediaCollection('logo_footer');
            } elseif ($request->has('logo_footer')) {
                $data_setting->addMediaFromRequest('logo_footer')->toMediaCollection('logo_footer');
            }
        } else {
            $data_setting = Setting::create([
                'facebook_link' => $request['facebook_link'],
                'instagram_link' => $request['instagram_link'],
                'twitter_link' => $request['twitter_link'],
                'linkedin_link' => $request['linkedin_link'],
                'tiktok_link' => $request['tiktok_link'],

                //infos application
                'projet_title' => $request['projet_title'],
                'projet_description' => $request['projet_description'],
                'phone1' => $request['phone1'],
                'phone2' => $request['phone2'],
                'phone3' => $request['phone3'],

                'email1' => $request['email1'],
                'email2' => $request['email2'],

                'localisation' => $request['localisation'],
                'google_maps' => $request['google_maps'],
                'siege_social' => $request['siege_social'],

                //security
                // 'mode_maintenance'=>'',
            ]);

            //insert image logo
            if ($request->has('logo_header')) {
                $data_setting->addMediaFromRequest('logo_header')->toMediaCollection('logo_header');
            }


            if ($request->has('logo_footer')) {
                $data_setting->addMediaFromRequest('logo_footer')->toMediaCollection('logo_footer');
            }
        }





        Alert::success('Operation réussi', 'Success Message');

        return back();
    }

    public function createBackup()
    {
        try {
            Artisan::call('backup:run');
            return response()->json(['success' => true, 'message' => 'Sauvegarde créée avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la création: ' . $e->getMessage()]);
        }
    }

    public function deleteBackup(Request $request)
    {
        try {
            $fileName = $request->file;
            $appName = config('app.name');
            $filePath = $appName . '/' . $fileName;

            if (Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
                return response()->json(['success' => true, 'message' => 'Sauvegarde supprimée']);
            }

            return response()->json(['success' => false, 'message' => 'Fichier introuvable']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
    }

    public function deleteAllBackups()
    {
        try {
            $appName = config('app.name');
            $files = Storage::disk('local')->files($appName);

            foreach ($files as $file) {
                Storage::disk('local')->delete($file);
            }

            return response()->json(['success' => true, 'message' => 'Toutes les sauvegardes supprimées']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
    }
}
