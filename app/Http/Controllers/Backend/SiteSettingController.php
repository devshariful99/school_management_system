<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteSettingRequest;
use App\Models\SiteSetting;
use App\Models\TempFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        // $this->middleware('permission:admin-list|admin-delete|admin-status', ['only' => ['index', 'show']]);
        // $this->middleware('permission:admin-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:admin-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:admin-status', ['only' => ['status']]);
    }
    public function index(): View
    {
        return view('backend.site_settings.index');
    }

    public function update(SiteSettingRequest $request): RedirectResponse
    {
        $data = $request->except('_token');
        try {
            $envPath = base_path('.env');
            $env = file($envPath);

            foreach ($data as $key => $value) {

                if ($key == 'site_logo' || $key == 'site_favicon') {
                    $temp_file = TempFile::findOrFail($request->$key);
                    if ($temp_file) {
                        $from_path = 'public/' . $temp_file->path . '/' . $temp_file->filename;
                        $to_path = 'site-settings/' . $key . '/' .  time() . '/' . $temp_file->filename;
                        Storage::move($from_path, 'public/' . $to_path);
                        $old_image = SiteSetting::where('key', $key)->first();
                        if ($old_image) {
                            $temp_create = new TempFile();
                            $temp_create->path =  dirname($old_image->value);
                            $temp_create->filename = basename($old_image->value);
                            $temp_create->from()->associate($old_image);
                            $temp_create->creater()->associate(admin());
                            $temp_create->save();
                        }
                        $siteSetting = SiteSetting::updateOrCreate(['key' => $key], ['value' => $to_path]);
                        Storage::deleteDirectory('public/' . $temp_file->path);
                        $temp_file->forceDelete();
                        continue;
                    }
                }
                // if ($key == 'site_favicon') {
                //     $temp_file = TempFile::findOrFail($request->site_favicon);
                //     if ($temp_file) {
                //         $from_path = 'public/' . $temp_file->path . '/' . $temp_file->filename;
                //         $to_path = 'site-settings/site-favicon/' . time() . '/' . $temp_file->filename;
                //         Storage::move($from_path, 'public/' . $to_path);
                //         $old_image = SiteSetting::where('key', 'site_favicon')->first();
                //         if ($old_image) {
                //             $temp_create = new TempFile();
                //             $temp_create->path =  dirname($old_image->value);
                //             $temp_create->filename = basename($old_image->value);
                //             $temp_create->from()->associate($old_image);
                //             $temp_create->creater()->associate(admin());
                //             $temp_create->save();
                //         }
                //         $siteSetting = SiteSetting::updateOrCreate(['key' => $key], ['value' => $to_path]);
                //         Storage::deleteDirectory('public/' . $temp_file->path);
                //         $temp_file->forceDelete();
                //         continue;
                //     }
                // }

                $siteSetting = SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);


                if (!empty($siteSetting->env_key)) {
                    $env = $this->set($siteSetting->env_key, '"' . $value . '"', $env);
                }
            }

            $fp = fopen($envPath, 'w');
            fwrite($fp, implode($env));
            fclose($fp);
            session()->flash('success', "Settings added successfully.");
            return redirect()->route('site_setting.index');
        } catch (\Exception $e) {
            session()->flash('error', "Something went wrong. Please try again.");
            return redirect()->route('site_setting.index');
        }
    }
    // public function sms_store(SmsSettingUpdateRequest $request): RedirectResponse
    // {
    //     $data = $request->except('_token');

    //     try {
    //         $envPath = base_path('.env');
    //         $env = file($envPath);
    //         foreach ($data as $key => $value) {
    //             $siteSetting = SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
    //             if (!empty($siteSetting->env_key)) {
    //                 $env = $this->set($siteSetting->env_key, '"' . $value . '"', $env);
    //             }
    //         }

    //         $fp = fopen($envPath, 'w');
    //         fwrite($fp, implode($env));
    //         fclose($fp);
    //         flash()->addSuccess('SMS Settings updated successfully.');
    //         return redirect()->route('settings.site_settings');
    //     } catch (\Exception $e) {
    //         flash()->addError('Something is wrong.');
    //         return redirect()->route('settings.site_settings');
    //     }
    // }

    // private function set($key, $value, $env)
    // {
    //     foreach ($env as $env_key => $env_value) {
    //         $entry = explode("=", $env_value, 2);
    //         if ($entry[0] == $key) {
    //             $env[$env_key] = $key . "=" . $value . "\n";
    //         } else {
    //             $env[$env_key] = $env_value;
    //         }
    //     }
    //     return $env;
    // }

    // public function notification(Request $request): RedirectResponse
    // {
    //     $keys = ['email_verification', 'sms_verification', 'user_registration', 'user_kyc'];

    //     foreach ($keys as $key) {
    //         if (isset($request->$key)) {
    //             SiteSetting::updateOrCreate(['key' => $key], ['value' => $request->$key]);
    //         } else {
    //             SiteSetting::updateOrCreate(['key' => $key], ['value' => 0]);
    //         }
    //     }
    //     flash()->addSuccess('Settings added successfully.');
    //     return redirect()->route('settings.site_settings');
    // }

    // public function et_edit($id)
    // {
    //     $data['email_template'] =  EmailTemplate::findOrFail($id);
    //     return response()->json($data);
    // }

    // public function et_update(EmailTemplateRequest $req, $id)
    // {
    //     try {
    //         $data = EmailTemplate::findOrFail($id);
    //         $data->subject = $req->subject;
    //         $data->template = $req->template;
    //         $data->update();
    //         flash()->addSuccess('Settings added successfully.');
    //         return response()->json(['message' => 'Email template updated successfully']);
    //     } catch (\Exception $e) {
    //         flash()->addError('Somethings is wrong.');
    //         return response()->json(['message' => 'An error occurred'], 500);
    //     }
    // }
    // public function ps_update(PointSettingRequest $request)
    // {
    //     $data = $request->except('_token');
    //     try {
    //         foreach ($data as $key => $value) {
    //             PointSetting::updateOrCreate(['key' => $key], ['value' => $value]);
    //         }
    //         $ph = PointHistory::latest()->first();
    //         if (!$ph) {
    //             PointHistory::create(['eq_amount' => $request->equivalent_amount, 'created_by' => admin()->id]);
    //         } elseif ($ph->eq_amount != $request->equivalent_amount) {
    //             PointHistory::activated()->update(['status' => 0, 'updated_by' => admin()->id]);
    //             PointHistory::create(['eq_amount' => $request->equivalent_amount, 'created_by' => admin()->id]);
    //         }
    //         flash()->addSuccess('Point settings added successfully.');
    //         return redirect()->route('settings.site_settings');
    //     } catch (\Exception $e) {
    //         flash()->addError('Something went wrong.');
    //         return redirect()->route('settings.site_settings');
    //     }
    // }
}
