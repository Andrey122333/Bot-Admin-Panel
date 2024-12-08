<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SettingsController extends Controller
{

    public function view()
    {
        $settings = Settings::firstOrNew([]);
        $response = '';
        $webhookData = '';

        return view('settings')->with([
            'settings' => $settings,
            'response' => $response
        ]);
    }

    public function settings(Request $req)
    {
        $validation = $req->validate([
            'token' => 'required',
            'name' => 'required'
        ]);

        $settings = Settings::updateOrCreate([], [
            'token' => $req->input('token'),
            'name' => $req->input('name')
        ]);


        $response = $this->setWebhook($req->input('token'));

        $webhookData = '';
        if ($settings->token) {
            $webhookData = $this->getWebhookInfo($settings->token);
        }

        return view('settings')->with([
            'settings' => $settings,
            'response' => $response,
            'webhookData' => $webhookData
        ]);
    }

    public function setWebhook($token, $useDropPendingUpdates = false)
    {
        $request = Request::capture();
        $hostname = $request->getHost();
        $url = 'https://api.telegram.org/bot' . $token . '/setWebhook?url=https://' . $hostname . '/api/tg';
        if ($useDropPendingUpdates) {
            $url .= '&drop_pending_updates=true';
        }
        $response = Http::get($url);
        return $response;
    }


    public function getWebhookInfo()
    {
        $settings = Settings::firstOrNew([]);

        if ($settings->token) {
            $url = "https://api.telegram.org/bot{$settings->token}/getWebhookInfo";
            $response = Http::get($url)->json();

            if (isset($response['result'])) {
                return response()->json($response['result']);
            } else {
                return response()->json(['error' => 'Result key not found in response']);
            }
        } else {
            return response()->json(['error' => 'Token is not set']);
        }
    }


    public function resetPendingUpdateCount()
    {
        $settings = Settings::firstOrNew([]);
        if ($settings->token) {
            $response = $this->setWebhook($settings->token, true);
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Token is not set']);
        }
    }
}
