<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Typedepense;
use App\Models\v_depenses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DepenseController extends Controller
{
            //liste disque Depense
            public function listeDepense(){
                $perPage = 10; // Nombre d'éléments par page
                $currentPage = request()->query('page', 1); // Page actuelle

                $liste = v_depenses::paginate($perPage, ['*'], 'page', $currentPage);

                return view('crud/Depense/Liste', [
                    'liste' => $liste,
                ]);
            }



            public function formsave()
            {

                $data = Typedepense::all();
                return view('crud/Depense/Form',[

                 'data' => $data
             ]);
            }

            public function create(Request $request)
            {
                $erreur=false;
                $liberreur='';
                if(substr($request->input('montant'), 0, 1)=='-'){
                    $erreur=true;
                    $liberreur='montant invalide';
                }
                $data = Typedepense::all();
                $valide=false;
                foreach($data as $data){
                    if($data->code==$request->input('idtypedepense')){
                        $valide=true;
                        break;
                    }
                }
                if($valide==false){
                    $erreur=true;
                    $liberreur=$liberreur.' Type depense invalide';
                }
                

                $mois = $request->input('mois');
                $datesInvalides = array();

                foreach ($mois as $moisValue) {
                    if (checkdate($moisValue, $request->input('jour'), $request->input('annee')) == false) {
                        // Formater la date invalide et la stocker dans le tableau $datesInvalides
                        $dateInvalide = sprintf("%02d-%02d-%04d", $request->input('jour'), $moisValue, $request->input('annee'));
                        $datesInvalides[] = $dateInvalide;
                    }
                }

                if (count($datesInvalides) > 0) {
                    // Si des dates invalides ont été trouvées, rediriger avec le message d'erreur contenant les dates invalides
                    $datesInvalidesStr = implode(", ", $datesInvalides);
                    return redirect()->back()->with('erreur', $liberreur." Dates invalides : $datesInvalidesStr");
                }
                if($erreur==true){
                    return redirect("listeDepense")->with('erreur',$liberreur);
                }
                $m=$request->input('mois');

                foreach($m as $mois){
                    $timestamp = Carbon::create($request->input('annee'), $mois, $request->input('jour'));
                    $depense=depense::create([
                        'idtypedepense' =>$request->input('idtypedepense'),
                        'montant'=>$request->input('montant'),
                        'nombre'=>$request->input('nombre'),
                        'date_depense'=>$timestamp
                    ]);
                }

                return redirect("listeDepense")->with('success','Information enregistree');

            }





            public function importCSV(Request $request)
            {
                if ($request->hasFile('csv')) {
                    $file=$request->file('csv');
                    $handle=fopen($file->getPathname(), 'r');
                    $table_data=array();
                    while(($data=fgetcsv($handle, 0, ';')) !==false) {
                        $values=explode(';',$data[0]);
                        $moment=Carbon::createFromFormat('d/m/Y',$values[0])->format('Y-m-d');
                        $type=Typedepense::firstWhere('code',$values[1]);
                        // dd($type->id);
                        $table_data[]=[
                            'idtypedepense'=> $type->idtypedepense,
                            'montant'=>$values[2],
                            'date_depense'=>$moment
                        ];
                    }
                    fclose($handle);
                    DB::table('depense')->insert($table_data);
                    return redirect()->back()->with('succes','Enregistrer');

                }
                else  return redirect()->back()->with('succes','Enregistrer');
            }

        //suprimer un Depense
            public function supprimerDepense()
            {
                $id = Depense::find(request('id'));
                $id->delete();
                return redirect("listeDepense")->with('suppression', ' Supprimé avec succes !');
            }

            //form vers ajout

            public function ajoutformDepense()
            {

            $data = Typedepense::all();
               return view('crud/Depense/Ajout',[

                'data' => $data
            ]);
            }

            //ajouter un  Depense
            public function ajoutDepense (Request $request){
                $data = $request->all();
                Depense::create($data);
                return redirect("listeDepense")->with('success', 'Ajout du depense avec succes !');
                }

                //form vers modifier
                public function modifformDepense($id){

                    $data = Depense::find($id);
                    $data1 = Typedepense::all();
                    return view('crud/Depense/Modif',[
                        'data' => $data,
                        'data1' => $data1

                    ]);


                }

                //modifier  Depense

                public function modifDepense(Request $request)
                {
                    $data2 = $request->all();
                    $item = Depense::findOrFail(request('iddepense'));
                    $item->update($data2);
                    return redirect("listeDepense")->with('modification', 'Modification effectué avec succes !');
                }



}
