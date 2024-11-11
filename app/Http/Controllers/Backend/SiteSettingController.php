<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailTemplateRequest;
use App\Http\Requests\Admin\SiteSettingRequest;
use App\Models\EmailTemplate;
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
                        $site_setting = SiteSetting::updateOrCreate(['key' => $key], ['value' => $to_path]);
                        Storage::deleteDirectory('public/' . $temp_file->path);
                        $temp_file->forceDelete();
                        continue;
                    }
                }
                $site_setting = SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);


                if (!empty($site_setting->env_key)) {
                    $env = $this->set($site_setting->env_key, '"' . html_entity_decode($value) . '"', $env);
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

    private function set($key, $value, $env)
    {
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            if ($entry[0] == $key) {
                $env[$env_key] = $key . "=" . $value . "\n";
            } else {
                $env[$env_key] = $env_value;
            }
        }
        return $env;
    }
    public function et_edit($id)
    {
        $data['email_template'] =  EmailTemplate::findOrFail($id);
        return response()->json($data);
    }

    public function et_update(EmailTemplateRequest $req, $id)
    {
        try {
            $data = EmailTemplate::findOrFail($id);
            $data->subject = $req->subject;
            $data->template = $req->template;
            $data->update();
            session()->flash('success', "Email template updated successfully.");
            return response()->json(['message' => 'Email template updated successfully']);
        } catch (\Exception $e) {
            session()->flash('error', "Something went wrong. Please try again.");
            return response()->json(['message' => 'Something went wrong. Please try again.'], 500);
        }
    }
    public function notification(Request $request): RedirectResponse
    {
        $keys = ['email_verification', 'sms_verification', 'user_registration'];
        foreach ($keys as $key) {
            if (isset($request->$key)) {
                SiteSetting::updateOrCreate(['key' => $key], ['value' => $request->$key]);
            } else {
                SiteSetting::updateOrCreate(['key' => $key], ['value' => 0]);
            }
        }
        session()->flash('success', "Notification setting update successfully.");
        return redirect()->route('site_setting.index');
    }
}
