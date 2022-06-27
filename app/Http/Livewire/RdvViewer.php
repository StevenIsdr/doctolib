<?php

namespace App\Http\Livewire;

use App\Models\Blog;
use App\Models\Demande;
use App\Notifications\ConfirmDemande;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RdvViewer extends Component
{
    public $rdvs;

    public function mount(){
        $this->refresh();
    }

    public function refresh(){
        $demandes = Demande::where('docteur_id',Auth::user()->id)->orderBy('date')->get()->toArray();
        $test  = ["newdate" => "null"];
        $this->rdvs =  array_map(function($demandes) use($test) {
            return array_merge($demandes, $test);},
            $demandes);
    }

    public function annuler($key){
        $rdv = Demande::find($this->rdvs[$key]['id']);
        if($rdv->docteur_id == Auth::user()->id){
            $rdv->status = -1;
            $rdv->save();
        }
    }

    public function accept($key){
        $rdv = Demande::find($this->rdvs[$key]['id']);
        if($rdv->docteur_id == Auth::user()->id){
            $rdv->status = 1;
            $rdv->save();
        }
        $client = getClient(Auth::user());
        if ($client instanceof Google_Client) {
            $service = new Google_Service_Calendar($client);
            $evt = new Google_Service_Calendar_Event(array(
                'summary' => "Rendez vous médical de ".$rdv->patient->name,
                'start' => array(
                    'dateTime' => Carbon::create($rdv->date)->format('Y-m-d\TH:i:s'),
                    'timeZone' => 'Europe/Paris',
                ),
                'end' => array(
                    'dateTime' => Carbon::create($rdv->date)->addHour()->format('Y-m-d\TH:i:s'),
                    'timeZone' => 'Europe/Paris',
                ),
            ));
            $service->events->insert('primary', $evt);
        }
        $rdv->patient->notify(new ConfirmDemande($rdv));
        $this->refresh();
    }

    public function refuse($key){
        $rdv = Demande::find($this->rdvs[$key]['id']);
        if($rdv->docteur_id == Auth::user()->id){
            $rdv->status = -1;
            $rdv->save();
        }
        $this->refresh();
    }

    public function completeAndCreate($key){
        $rdvUpdate = $this->rdvs[$key];
        $rdvToUpdate = Demande::find($rdvUpdate['id']);
        $rdvToUpdate->rapport = $rdvUpdate['rapport'];
        $rdvToUpdate->status = 2;
        $rdvToUpdate->save();

        Demande::create([
            'raison' => $rdvUpdate['raison'],
            'details' => $rdvUpdate['details'],
            'date' => $rdvUpdate['newdate'],
            'status' => 1,
            'demandeur_id' => $rdvUpdate['demandeur_id'],
            'docteur_id' => Auth::user()->id
        ]);

        $this->refresh();
    }

    public function render()
    {
        return view('livewire.rdv-viewer');
    }
}
