<?php

namespace App\Http\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Google\Collection;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class Horaires extends Component
{

    public $events;

    public $numberPerWeek = 5;
    public $totalDays = 28;
    public $countWeek = 1;
    public $duree = 30;
    public $startDate;
    public $initialDate;
    public $dates;
    public $isAfter;
    public $hours;
    public $entreprises;

    public $date = null;
    public $heure = null;

    /**
     * @throws \Exception
     */
    public function mount()
    {
        $this->events = collect();
        $this->hours = collect();
        $this->dates = [];
        $this->isAfter = false;
        $this->startDate = Carbon::now()->addDay()->tz('Europe/Paris');
        $this->initialDate = Carbon::now()->addDay()->tz('Europe/Paris');
        $docs = User::where('role',1)->get();
        foreach ($docs as $key => $user) {
            $client = getClient($user);
            if ($client instanceof Google_Client) {
                $service = new Google_Service_Calendar($client);
            } else {
                abort(400);
            }
            $optParams = array(
                'timeMin' => Carbon::now()->format('Y-m-d\T00:00:00.u\Z'),
                'orderBy' => 'startTime',
                'singleEvents' => true);
            $eventsNCol = $service->events->listEvents('primary', $optParams);
            $this->events = collect();
            while (true) {
                foreach ($eventsNCol->getItems() as $event) {
                    if (!$event->recurence && $event->getStart()) {
                        $this->events->push([
                            'date_debut' => Carbon::create($event->getStart()->dateTime, 'Europe/Paris'),
                            'date_fin' => Carbon::create($event->getEnd()->dateTime, 'Europe/Paris')]);
                    }
                }

                $pageToken = $eventsNCol->getNextPageToken();
                if ($pageToken) {
                    $optParams = array(
                        'pageToken' => $pageToken,
                        'timeMin' => Carbon::now()->format('Y-m-d\T00:00:00.u\Z'),
                        'orderBy' => 'startTime',
                        'singleEvents' => true);
                    $eventsNCol = $service->events->listEvents('primary', $optParams);
                } else {
                    break;
                }
            }

            for ($i = 0; $i < $this->totalDays; $i++) {
                $tempDate = $this->startDate->copy()->addDays($i);
                if ($i < $this->numberPerWeek) {
                    if (!in_array($tempDate, $this->dates)) {
                        $this->dates[] = $tempDate;
                    }
                }

                $key = $tempDate->locale('fr_FR')->dayName;
                $tempEvents = $this->events->whereBetween('date_debut', [$tempDate->copy()->setTime(0, 0, 0, 0), $tempDate->copy()->setTime(23, 59, 59)]);
                $previous = [
                    'date_debut' => Carbon::create($tempDate->copy()->setTime(0, 0, 0, 0), 'Europe/Paris'),
                    'date_fin' => Carbon::create($tempDate->copy()->setTime(8, 0, 0, 0), 'Europe/Paris')
                ];

                $entreprisePlage = collect(json_decode($user->plage));
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


                $heures = collect();

                $tempEvents = $tempEvents->sortBy('date_debut');
                $tempEvents = $tempEvents->values();

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

                    if (($diff = $previous['date_fin']->diffInMinutes($event['date_debut'])) >= $this->duree) {
                        $entier = floor($diff / $this->duree);
                        for ($j = 0; $j < $entier; $j++) {
                            $timer = $previous['date_fin']->copy()->addMinutes($j * $this->duree);
                            if ($this->hours->has($tempDate->format('Ymd'))) {
                                if (!$this->hours[$tempDate->format('Ymd')]->contains($timer->format('H:i'))) {
                                    $this->hours[$tempDate->format('Ymd')]->push($timer->format('H:i'));
                                }
                            } else {
                                $heures->push($timer->format('H:i'));
                            }
                        }
                    }
                    $previous = $event;
                }
                if (!$this->hours->has($tempDate->format('Ymd'))) {
                    $this->hours->put($tempDate->format('Ymd'), $heures);
                }
            }
        }
        $this->hours = $this->hours->map(function ($value, $key) {
            return $value->sortBy(function ($val, $key) {
                return intval(str_replace(":", "", $val));
            })->values();
        });

    }

    public function addSemaine()
    {
        $this->dates = [];
        $this->countWeek++;

        $this->initialDate = $this->initialDate->addDays($this->numberPerWeek);
        if ($this->initialDate->gt($this->startDate))
            $this->isAfter = true;

        for ($i = 0; $i < $this->numberPerWeek; $i++) {
            $tempDate = $this->initialDate->copy()->addDays($i);
            $this->dates[] = $tempDate;
        }
    }

    public function removeSemaine()
    {
        $this->dates = [];
        $this->countWeek--;

        $this->initialDate = $this->initialDate->subDays($this->numberPerWeek);
        if ($this->initialDate->lte($this->startDate))
            $this->isAfter = false;

        for ($i = 0; $i < $this->numberPerWeek; $i++) {
            $tempDate = $this->initialDate->copy()->addDays($i);
            $this->dates[] = $tempDate;
        }
    }


    public function render()
    {
        return view('livewire.horaires');
    }
}
