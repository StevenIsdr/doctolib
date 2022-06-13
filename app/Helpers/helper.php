<?php

use App\Models\Demande;
use App\Models\Pivot_userVille;
use App\Models\User;
use Carbon\Carbon;

/**
 * @throws Exception
 */
function getClient(User $user)
{
    $client = new \Google_Client();
    $client->setApplicationName('SoGoodVirtual');
    $client->setScopes([Google_Service_Calendar::CALENDAR_EVENTS]);
    $client->setAuthConfig(storage_path('sogoodvirtual_google.json'));
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    if ($user->access_token) {
        $client->setAccessToken($user->access_token);
    }

    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
// $user = \Auth::user();
            $user->access_token = json_encode($client->getAccessToken());
            $user->save();
        } else {
            $authUrl = $client->createAuthUrl();
            session(['redirectToClientAuthURL' => url()->previous(), 'connectionForAnotherUser' => $user->id]);
            return redirect($authUrl);
        }
    } elseif ($client->isAccessTokenExpired()) {
        return null;
    }
    return $client;
}

function getSingleAdress($adresse)
{
    $url = "https://api-adresse.data.gouv.fr/search/";
    $dataArray = array("q" => $adresse);

    $ch = curl_init();
    $data = http_build_query($dataArray);
    $getUrl = $url . "?" . $data . "&type=housenumber&limit=1";

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $getUrl);
    curl_setopt($ch, CURLOPT_TIMEOUT, 80);

    $responses = json_decode(curl_exec($ch), true);
    return $responses;
}

function getEntreprise($entrepriseid, $day)
{
    $entreprises = collect();
    $hours = collect();

    foreach ($entrepriseid as $id) {
        $entreprises->push(Pivot_userVille::find($id));
    }

    $tempDate = Carbon::create($day);

    foreach ($entreprises as $entreprise) {
        $events = collect();
        $user = User::find($entreprise->user_id);
        $client = getClient($user);
        if ($client instanceof Google_Client) {
            $service = new Google_Service_Calendar($client);
        } else {
            abort(500);
        }
        $optParams = array(
            'timeMin' => $tempDate->copy()->format('Y-m-d\T00:00:00.u\Z'),
            'timeMax' => $tempDate->copy()->format('Y-m-d\T23:59:59.u\Z'),
            'orderBy' => 'startTime',
            'singleEvents' => true);
        $eventsNCol = $service->events->listEvents('primary', $optParams);
        while (true) {
            foreach ($eventsNCol->getItems() as $event) {
                if (!$event->recurence && $event->getStart()) {
                    $events->push([
                        'date_debut' => Carbon::create($event->getStart()->dateTime, 'Europe/Paris'),
                        'date_fin' => Carbon::create($event->getEnd()->dateTime, 'Europe/Paris')]);
                }

            }
            $pageToken = $eventsNCol->getNextPageToken();
            if ($pageToken) {
                $optParams = array(
                    'pageToken' => $pageToken,
                    'timeMin' => $tempDate->copy()->setHour(0)->setMinute(0)->setSecond(0)->format('Y-m-d\T00:00:00.u\Z'),
                    'timeMax' => $tempDate->copy()->setHour(23)->setMinute(59)->setSecond(59)->format('Y-m-d\T00:00:00.u\Z'),
                    'orderBy' => 'startTime',
                    'singleEvents' => true);
                $eventsNCol = $service->events->listEvents('primary', $optParams);

            } else {
                break;
            }
        }

        $key = $tempDate->dayName;
        $tempEvents = $events->whereBetween('date_debut', [$tempDate->copy()->setTime(0, 0, 0, 0), $tempDate->copy()->setTime(23, 59, 59)]);

        $previous = [
            'date_debut' => Carbon::create($tempDate->copy()->setTime(0, 0, 0, 0), 'Europe/Paris'),
            'date_fin' => Carbon::create($tempDate->copy()->setTime(8, 0, 0, 0), 'Europe/Paris')
        ];

        $entreprisePlage = collect(json_decode(User::find($entreprise->user_id)->entreprise->plage));

        foreach ($entreprisePlage[$key] as $plage) {
            $dateDebut = explode(":", $plage[0]);
            $dateFin = explode(":", $plage[1]);

            $tempEvents->push([
                'date_debut' => Carbon::create($tempDate->copy()->setTime($dateDebut[0], $dateDebut[1], 00), 'Europe/Paris'),
                'date_fin' => Carbon::create($tempDate->copy()->setTime($dateFin[0], $dateFin[1], 00), 'Europe/Paris')
            ]);
        }

        $tempEvents->push([
            'date_debut' => Carbon::create($tempDate->copy()->setTime(18, 30, 00), 'Europe/Paris'),
            'date_fin' => Carbon::create($tempDate->copy()->setTime(23, 59, 59), 'Europe/Paris')
        ]);


        $tempEvents = $tempEvents->sortBy('date_debut');
        $tempEvents = $tempEvents->values();
        $tempHeures = collect();

        foreach ($tempEvents as $event) {

            if ($event['date_debut'] < $previous['date_fin']) {
                if ($event['date_fin'] > $previous['date_fin']) {
                    $event['date_debut'] = $previous['date_fin'];
                } else {
                    continue;
                }
            }

            if ($previous['date_fin']->minute > 0 && $previous['date_fin']->minute < 30) {
                $previous['date_fin'] = $previous['date_fin']->setMinutes(30);
            } elseif ($previous['date_fin']->minute > 30 && $previous['date_fin']->minute <= 59) {
                $previous['date_fin'] = $previous['date_fin']->addHour()->setMinutes(0);
            }

            if (($diff = $previous['date_fin']->diffInMinutes($event['date_debut'])) >= 30) {
                $entier = floor($diff / 30);
                for ($j = 0; $j < $entier; $j++) {
                    $timer = $previous['date_fin']->copy()->addMinutes($j * 30);
                    if ($hours->has($tempDate->format('Ymd'))) {
                        if (!$hours[$tempDate->format('Ymd')]->contains($timer->format('H:i'))) {
                            $tempHeures[$tempDate->format('Ymd')]->push($timer->format('H:i'));
                        }
                    } else {
                        $tempHeures->push($timer->format('H:i'));
                    }
                }
            }
            $previous = $event;
        }
        if (!$hours->has($tempDate->format('Ymd'))) {
            $hours->push([$entreprise->id => $tempHeures]);
        }
    }


    $hourUser = explode(" ", $day)[1];
    $entreprisesDisponibles = [];
    foreach ($hours as $hour) {
        foreach ($hour as $hou) {
            if (in_array($hourUser, $hou->toArray())) {
                array_push($entreprisesDisponibles, key($hour));
            }
        }
    }
    return $entreprisesDisponibles;
}


function saveDemandeVerif($adresse, $superficie, $type, $services = null, $service_id, $date, $user, $entreprises)
{
    $responses = getSingleAdress($adresse);
    $latlon = array_column(array_column($responses['features'], "geometry"), "coordinates");
    $ville = array_column(array_column($responses['features'], "properties"), "city");

    $latititude = $latlon[0][1];
    $longitude = $latlon[0][0];

    $entreprises = explode(",", trim($entreprises, ','));

    $entreprisesids = getEntreprise($entreprises, $date);

    if ($entreprisesids && $latititude && $longitude) {
        $entreprise = $entreprisesids[array_rand($entreprisesids)];
        $expiration = Carbon::now()->addMinutes(30)->tz("Europe/Paris")->toDateTimeString();
        $prix = 149 + (floor($superficie / 100) * 80);
//        if($services){
//            dd($services);
//            foreach ($services as $service){
//                $prix += match ($service) {
//                    1 => 4,
//                    2 => 1,
//                    3 => 2,
//                    default => 0,
//                };
//            }
//        }
        Demande::create([
            'adresse' => $adresse,
            'ville' => $ville[0],
            'latitude' => $latititude,
            'longitude' => $longitude,
            'superficie' => $superficie,
            'type' => $type,
            'date' => $date,
            'services' => $services,
            'service_id' => $service_id,
            'prix_estime' => $prix,
            'userville_id' => $entreprise,
            'user_id' => $user->id,
            'expiration' => $expiration]);
    } else {
        abort(400);
    }
    return redirect('tableau-de-bord');
}

function getSingleEntrepriseValidation($entrepriseid, $day)
{
    $hours = collect();

    $tempDate = Carbon::create($day);
    $entreprise = Pivot_userVille::find($entrepriseid);

    $events = collect();

    $user = User::find($entreprise->user_id);
    $client = getClient($user);
    if ($client instanceof Google_Client) {
        $service = new Google_Service_Calendar($client);
    } else {
        abort(500);
    }
    $optParams = array(
        'timeMin' => $tempDate->copy()->format('Y-m-d\T00:00:00.u\Z'),
        'timeMax' => $tempDate->copy()->format('Y-m-d\T23:59:59.u\Z'),
        'orderBy' => 'startTime',
        'singleEvents' => true);
    $eventsNCol = $service->events->listEvents('primary', $optParams);
    while (true) {
        foreach ($eventsNCol->getItems() as $event) {
            if (!$event->recurence && $event->getStart()) {
                $events->push([
                    'date_debut' => Carbon::create($event->getStart()->dateTime, 'Europe/Paris'),
                    'date_fin' => Carbon::create($event->getEnd()->dateTime, 'Europe/Paris')]);
            }

        }
        $pageToken = $eventsNCol->getNextPageToken();
        if ($pageToken) {
            $optParams = array(
                'pageToken' => $pageToken,
                'timeMin' => $tempDate->copy()->setHour(0)->setMinute(0)->setSecond(0)->format('Y-m-d\T00:00:00.u\Z'),
                'timeMax' => $tempDate->copy()->setHour(23)->setMinute(59)->setSecond(59)->format('Y-m-d\T00:00:00.u\Z'),
                'orderBy' => 'startTime',
                'singleEvents' => true);
            $eventsNCol = $service->events->listEvents('primary', $optParams);

        } else {
            break;
        }
    }

    $key = $tempDate->dayName;
    $tempEvents = $events->whereBetween('date_debut', [$tempDate->copy()->setTime(0, 0, 0, 0), $tempDate->copy()->setTime(23, 59, 59)]);

    $previous = [
        'date_debut' => Carbon::create($tempDate->copy()->setTime(0, 0, 0, 0), 'Europe/Paris'),
        'date_fin' => Carbon::create($tempDate->copy()->setTime(8, 0, 0, 0), 'Europe/Paris')
    ];

    $entreprisePlage = collect(json_decode(User::find($entreprise->user_id)->entreprise->plage));

    foreach ($entreprisePlage[$key] as $plage) {
        $dateDebut = explode(":", $plage[0]);
        $dateFin = explode(":", $plage[1]);

        $tempEvents->push([
            'date_debut' => Carbon::create($tempDate->copy()->setTime($dateDebut[0], $dateDebut[1], 00), 'Europe/Paris'),
            'date_fin' => Carbon::create($tempDate->copy()->setTime($dateFin[0], $dateFin[1], 00), 'Europe/Paris')
        ]);
    }

    $tempEvents->push([
        'date_debut' => Carbon::create($tempDate->copy()->setTime(18, 30, 00), 'Europe/Paris'),
        'date_fin' => Carbon::create($tempDate->copy()->setTime(23, 59, 59), 'Europe/Paris')
    ]);


    $tempEvents = $tempEvents->sortBy('date_debut');
    $tempEvents = $tempEvents->values();
    $tempHeures = collect();

    foreach ($tempEvents as $event) {

        if ($event['date_debut'] < $previous['date_fin']) {
            if ($event['date_fin'] > $previous['date_fin']) {
                $event['date_debut'] = $previous['date_fin'];
            } else {
                continue;
            }
        }

        if ($previous['date_fin']->minute > 0 && $previous['date_fin']->minute < 30) {
            $previous['date_fin'] = $previous['date_fin']->setMinutes(30);
        } elseif ($previous['date_fin']->minute > 30 && $previous['date_fin']->minute <= 59) {
            $previous['date_fin'] = $previous['date_fin']->addHour()->setMinutes(0);
        }

        if (($diff = $previous['date_fin']->diffInMinutes($event['date_debut'])) >= 30) {
            $entier = floor($diff / 30);
            for ($j = 0; $j < $entier; $j++) {
                $timer = $previous['date_fin']->copy()->addMinutes($j * 30);
                if ($hours->has($tempDate->format('Ymd'))) {
                    if (!$hours[$tempDate->format('Ymd')]->contains($timer->format('H:i'))) {
                        $tempHeures[$tempDate->format('Ymd')]->push($timer->format('H:i'));
                    }
                } else {
                    $tempHeures->push($timer->format('H:i'));
                }
            }
        }
        $previous = $event;
    }
    if (!$hours->has($tempDate->format('Ymd'))) {
        $hours->push([$entreprise->id => $tempHeures]);
    }


    $hourUser = explode(" ", $day)[1];
    $isStillValid = false;
    foreach ($hours as $hour) {
        foreach ($hour as $hou) {
            if (in_array($hourUser, $hou->toArray())) {
                $isStillValid = true;
            }
        }
    }
    return $isStillValid;
}


?>
