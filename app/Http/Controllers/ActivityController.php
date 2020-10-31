<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Validator;

class ActivityController extends Controller
{
    private function validate_input($input){
        return Validator::make($input, [
            'name' => 'required',
            'method_id' => 'required|exists:methods,id',
            'started_date' => 'required|date',
            'ended_date' => 'required|date|after_or_equal:started_date'
        ]); 
    }

    private function grouped_by_month($data){
        $result = [];
        foreach ($data as $item) {
            // based on date format YYYY-MM-DD
            $started_month = (int)explode('-', $item->started_date)[1];
            $ended_month = (int)explode('-', $item->ended_date)[1];
            $month = $started_month;

            do {
                if(empty($result["month-".$month]))
                    $result["month-".$month] = [$item];
                else
                    $result["month-".$month][] = $item;

                if($started_month == $ended_month){
                    $month = null;
                } else {
                    $month = $ended_month;
                    // for end looping
                    $ended_month = $started_month;
                }

            } while( $month != null );

        }

        return $result;
    }

    private function grouped_by_method($data){
        $result = [];
        foreach ($data as $item) {
            if(empty($result["method-".$item->method_id]))
                $result["method-".$item->method_id] = [$item];
            else
                $result["method-".$item->method_id][] = $item;
        }

        foreach ($result as $key => $item) {
            $result[$key] = $this->grouped_by_month($item);
        }

        return $result;
    }
    /**
     * Display a listing of the resource.
     *
     * Params (GET) :
     * - year = year of activities
     * - period = period in a year (1 : January - June, 2 : July - December) 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if(empty($request->get('year')) || empty($request->get('period')))
            return response()->json([]);

        $month_comparator = $request->get('period') > 1 ? ">" : "<=";

        $activities = Activity::where(function($q) use ($request, $month_comparator) {
            $q->whereYear('started_date', '=', $request->get('year'));
            $q->whereMonth('started_date', $month_comparator, 6);
        })
        ->orWhere(function($q) use ($request, $month_comparator){
            $q->whereYear('ended_date', '=', $request->get('year'));
            $q->whereMonth('ended_date', $month_comparator, 6);
        })
        ->get();

        return response()->json($this->grouped_by_method($activities));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $input = $request->all();

        $validator = $this->validate_input($input);  

        if($validator->fails())
            return response()->json([
                'errors' => $validator->errors()->getMessages(),
                'success' => false
            ]); 

        Activity::create($input);      

        return response()->json(['success' => true]);     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $input = $request->all();

        $validator = $this->validate_input($input);  

        if($validator->fails())
            return response()->json([
                'errors' => $validator->errors()->getMessages(),
                'success' => false
            ]); 

        Activity::where('id', $id)->update($input);    

        return response()->json(['success' => true]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $activity = Activity::find($id);
        
        if($activity == null)
            return response()->json(['success' => false]);

        $activity->delete();

        return response()->json(['success' => true]);
    }
}
