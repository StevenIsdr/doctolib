<?php

namespace App\Http\Livewire;

use App\Models\Entreprise;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Google\Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EntrepriseHoraire extends Component
{
    public $dates;
    public $hours = ["08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30", "18:00", "18:30"];
    public $schedule;

    public function mount()
    {
        $this->schedule = json_decode(Auth::user()->dispo);
        $this->dates = [0 => "Lun", 1 => "Mar", 2 => "Mer", 3 => "Jeu", 4 => "Ven", 5 => "Sam", 6 => "Dim"];
    }

    public function select($day, $hour)
    {
        if (!in_array($hour, $this->schedule[$day])) {
            array_push($this->schedule[$day], $hour);
        } else {
            array_splice($this->schedule[$day], array_search($hour, $this->schedule[$day]), 1);
        }
    }

    public function save()
    {
        $nSchedule = [];
        foreach ($this->schedule as $sDay) {
            usort($sDay, function ($a, $b) {
                return (strtotime($a) > strtotime($b));
            });
            $array = array_diff($this->hours,$sDay);
            array_push($nSchedule, array_values($array));
        }
        $creneaux = collect();
        for ($j = 0; $j < count($nSchedule); $j++) {
            $creneauDebut = -1;
            $creneauFin = -1;
            for ($i = 0; $i + 1 < count($nSchedule[$j]); $i++) {
                if (strtotime($nSchedule[$j][$i] . "+ 30 minutes") == strtotime($nSchedule[$j][$i + 1])) {
                    $creneauDebut = $nSchedule[$j][$i];
                    while ($i + 1 < count($nSchedule[$j]) && strtotime($nSchedule[$j][$i] . "+ 30 minutes") == strtotime($nSchedule[$j][$i + 1])) {
                        $creneauFin = $nSchedule[$j][$i + 1];
                        $i++;
                    }
                    $creneauxJour[] = [$creneauDebut, $creneauFin];
                }
            }
            $creneaux->put($this->getDayName($j),$creneauxJour);
            $creneauxJour = [];
        }

        $doc = Auth::user();
        $doc->dispo = json_encode($this->schedule);
        $doc->plage = $creneaux->toJson();
        $doc->save();

    }

    function getDayName($index)
    {
        switch ($index) {
            case 0:
                return "lundi";
                break;
            case 1:
                return "mardi";
                break;
            case 2:
                return "mercredi";
                break;
            case 3:
                return "jeudi";
                break;
            case 4:
                return "vendredi";
                break;
            case 5:
                return "samedi";
                break;
            case 6:
                return "dimanche";
                break;
        }
    }

    public function render()
    {
        return view('livewire.entreprise-horaire');
    }
}
