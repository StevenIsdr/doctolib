<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Entreprise;
use App\Models\User;
use App\Notifications\EntrepriseInfoDispo;
use Carbon\Carbon;
use Google\Exception;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    /**
     * @throws Exception
     * @throws \Exception
     */

    public function test(){
        $date = Carbon::create("2022-06-11 10:30")->tz('Europe/Paris');
        $entreprise = Entreprise::find(1);

        if($entreprise->pending){
            $array = collect(json_decode($entreprise->pending));
            $array->put($date->format('Y-m-d'),collect($array[$date->format('Y-m-d')])->push([$date->copy()->subMinutes(30)->toDateTimeString(),$date->copy()->addHours(2)->addMinutes(30)->toDateTimeString()]));
            $entreprise->pending = $array->toJson();
        } else {
            $array = collect();
            $array->put( $date->format('Y-m-d'),[collect()->push($date->copy()->subMinutes(30)->toDateTimeString(),$date->copy()->addHours(2)->addMinutes(30)->toDateTimeString())]);
            $entreprise->pending = $array->toJson();
        }
//        dump($array->toArray());
//        //$array->put( $date->format('Y-m-d'),collect()->push(collect($array[$date->format('Y-m-d')])->toArray()[0],[$date->copy()->subMinutes(30)->toDateTimeString(),$date->copy()->addHours(2)->addMinutes(30)->toDateTimeString()]));
//        dump($array->toArray());
//        $array->put($date->format('Y-m-d'),collect($array[$date->format('Y-m-d')])->push([$date->copy()->subMinutes(30)->toDateTimeString(),$date->copy()->addHours(2)->addMinutes(30)->toDateTimeString()])->toArray());
        $entreprise->save();
        dump($array->toArray());
        dd("stop");
    }

    public function getClient()
    {
        return getClient(Auth::user());
    }

//    public function test()
//    {
//        $client = getClient(Auth::user());
//
//        if ($client instanceof Google_Client) {
//            $service = new Google_Service_Calendar($client);
//        } else {
//            dd($client);
//        }
//        $optParams = array('timeMin' => Carbon::now()->toRfc3339String());
//        $events = $service->events->listEvents('primary', $optParams);
//
//        while(true) {
//            foreach ($events->getItems() as $event) {
//                dump($event->getStart());
//            }
//            $pageToken = $events->getNextPageToken();
//            if ($pageToken) {
//                $optParams = array('pageToken' => $pageToken,'timeMin' => Carbon::now()->toRfc3339String());
//                $events = $service->events->listEvents('primary', $optParams);
//            } else {
//                break;
//            }
//        }
//    }

    public function deleteClient()
    {
        $user = auth()->user();
        $user->access_token = null;
        $user->save();
        return redirect("tableau-de-bord/horaires");
    }

    public function callback(Request $request)
    {
        if (isset($request['error'])) {
            abort(500);
        }

        $client = new Google_Client();
        $client->setApplicationName('XenoDoc');
        $client->setScopes([Google_Service_Calendar::CALENDAR_EVENTS]);
        $client->setAuthConfig(storage_path('xenodoc.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $token = $client->authenticate($request['code']);

        if (isset($token['error'])) {
            abort(500);
        }

        $user = auth()->user();
        $user->access_token = json_encode($token);
        $user->save();

        return redirect("/tableau-de-bord/horaires");
    }
}
