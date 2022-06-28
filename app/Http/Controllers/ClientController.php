<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Notifications\CancelDemande;
use Google\Service\AndroidPublisher\DeactivateBasePlanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\AbstractList;

class ClientController extends Controller
{
    public function index()
    {
        if (Auth::user()->isDoc()) {
            return view('doc.doc');
        } else {
            $rdvs = Demande::where('demandeur_id', Auth::user()->id)->get();
            return view('client.client',['rdvs' => $rdvs]);
        }
    }

    public function cancel($id){
        $dmd = Demande::find($id);
        if($dmd->demandeur_id == Auth::user()->id){
            $dmd->status = -1;
            $dmd->save();
            $dmd->doc->notify(new CancelDemande($dmd));
            return redirect('tableau-de-bord/mes-rdv');
        }
    }

    public function mesRdv(){
        $rdvs = Demande::where('demandeur_id', Auth::user()->id)->get();
        return view('client.rdv',['rdvs' =>  $rdvs]);
    }

    public function rdv(){
        return view('doc.mes-rdv');
    }

    public function horaires()
    {
        return view('doc.horaires');
    }

    public function saveRdv(Request $request){
        $request->validate([
            'raison' => 'required',
            'timestamp' => 'required',
            'rdv' => 'required',
        ]);
        saveDemandeVerif($request->timestamp,$request->raison,$request->rdv);
        return redirect('tableau-de-bord');
    }
}
