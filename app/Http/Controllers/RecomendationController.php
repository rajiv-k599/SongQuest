<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;
use App\Models\song;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;



class RecomendationController extends Controller
{
    //
    public function index() {
        $song = song::all();
        
        return view('recommendor.recommendation.recommendation', ['song' => $song]);
    }

    public function create(Request $request){
        $userId = Auth::id();

        Recommendation::create([
        'recommendation_name' => $request->recommendation_name,
        'recommendation_for' => $request->recommendation_for,
        'recommendation_1' => $request->recommendation_1,
        'recommendation_2' => $request->recommendation_2,
        'recommendation_3' => $request->recommendation_3,
        'user_id' => $userId 
        ]);


        Alert::success('Success', 'Custom Recommendation added successfully.');
        // Alert::error('Error Message', 'Optional Title');
        // Alert::warning('Warning Message', 'Optional Title');
        // Alert::info('Info Message', 'Optional Title');
        // Alert::question('Question Message', 'Optional Title');

        
        return redirect()->route('recommender.recommendation');
    }

    public function myrecommendation(Request $request){
        $userId=Auth::id();
        $recommendation_list= Recommendation::join('songs as songs0', 'recommendations.recommendation_for', '=', 'songs0.id')
                                        ->join('songs as songs1', 'recommendations.recommendation_1', '=', 'songs1.id')
                                       ->join('songs as songs2', 'recommendations.recommendation_2', '=', 'songs2.id')
                                       ->join('songs as songs3', 'recommendations.recommendation_3', '=', 'songs3.id')
                                       
                                        ->select(
                                            'recommendations.*',
                                            'songs0.title as recommendation_for_name',
                                            'songs1.title as recommendation_1_name',
                                            'songs2.title as recommendation_2_name',
                                            'songs3.title as recommendation_3_name'
                                        ) ->where('recommendations.user_id','=',$userId) ->get();
        return view('recommendor.recommendation.myrecommendation',["recommendations"=>$recommendation_list]);
    }
}
