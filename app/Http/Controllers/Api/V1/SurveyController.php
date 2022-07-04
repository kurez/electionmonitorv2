<?php

namespace App\Http\Controllers\Api\V1;

use App\UserCode;
use Illuminate\Http\Request;
use DB;

class SurveyController extends APIController
{


    /**
     * store survey
     *
     * @return response()
     */
    public function storeSurvey(Request $request)
    {
    
    
	 $save = DB::table('survey_set')->insert(
	    ['title' => $request->input('title'),
	     'description' => $request->input('description'),
	     'start_date' => $request->input('start_date'),
	     'end_date' => $request->input('end_date'),]
	);
	
	if ($save){
         return response()->json(['status' => 'Successful','message' => 'Survey saved successfully']);
        }else {
         return response()->json(['status' => 'Failed','message' => 'Survey was not saved']);
         }
         
    } /**
     * fetch surveys
     *
     * @return response()
     */
    public function fetchSurveys()
    {
	 $surveys = DB::table('survey_set')->get();
	
	if ($surveys){
         return response()->json(['status' => 'Successful','message' => 'Surveys fetched successfully', 'data' => $surveys]);
        }else {
         return response()->json(['status' => 'Failed','message' => 'Surveys not fetched']);
         }
         
    }

    /**
     * fetch surveys
     *
     * @return response()
     */
    public function fetchByID($id)
    {
	 $surveys = DB::table('survey_set')->where('id', $id)->get();
	
	if ($surveys){
         return response()->json(['status' => 'Successful','message' => 'Surveys fetched successfully', 'data' => $surveys]);
        }else {
         return response()->json(['status' => 'Failed','message' => 'Surveys not fetched']);
         }
         
    }
        /**
     * update announcement
     *
     * @return response()
     */
    public function updateSurvey(Request $request,$id)
    {
       $update = DB::table('survey_set')->where('id', $id)->update([
       	'title' => $request->input('title'),
	     'description' => $request->input('description'),
	     'start_date' => $request->input('start_date'),
	     'end_date' => $request->input('end_date')]);
  
        if ($update){
         return response()->json(['status' => 'Successful','message' => 'Survey updated successfully']);
		}else {
		 return response()->json(['status' => 'Failed','message' => 'Survey was not updated']);
		 }
         
    }
    /**
     * fetch survey questions
     *
     * @return response()
     */
    public function fetchSurveyQuestions(Request $request)
    {
	 $questions = DB::table('questions')->get();
	
	if ($questions){
         return response()->json(['status' => 'Successful','message' => 'Survey questions fetched successfully', 'data' => $questions]);
        }else {
         return response()->json(['status' => 'Failed','message' => 'Survey questionss not fetched']);
         }
         
    }

     /**
     * fetch survey questions
     *
     * @return response()
     */
    public function fetchQuestionByID(Request $request, $id)
    {
	 $questions = DB::table('questions')->where('id', $id)->get();
	
	if ($questions){
         return response()->json(['status' => 'Successful','message' => 'Questions fetched successfully', 'data' => $questions]);
        }else {
         return response()->json(['status' => 'Failed','message' => 'Survey questions not fetched']);
         }
         
    }

     /**
     * fetch survey questions 
     *
     * @return response()
     */
    public function fetchSurveyQuestionsByID(Request $request, $id)
    {
	 $questions = DB::table('questions')->where('survey_id', $id)->get();
	
	if ($questions){
         return response()->json(['status' => 'Successful','message' => 'Survey questions fetched successfully', 'data' => $questions]);
        }else {
         return response()->json(['status' => 'Failed','message' => 'Survey questionss not fetched']);
         }
         
    }
    /**
     * delete survey
     *
     * @return response()
     */
    public function deleteSurvey(Request $request, $id)
    {
       $delete =  DB::table('survey_set')->delete($id);
  
        if ($delete){
         return response()->json(['status' => 'Successful','message' => 'Survey deleted successfully']);
		}else {
		 return response()->json(['status' => 'Failed','message' => 'Survey was not deleted']);
		 }
         
    }
        /**
     * save question
     *
     * @return response()
     */
    public function saveQuestion(Request $request)
    {
    if($request->input('type') != 'textfield_s'){
	$arr = array();
		foreach ($request->input('label') as $k => $v) {
			$i = 0 ;
			while($i == 0){
			// $k = substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,5);
               
				if(!isset($arr[$k]))
					$i = 1;
					}
					$arr[$k] = $v;
				}
				
	 $save = DB::table('questions')->insert(
	    ['question' => $request->input('question'),
	     'frm_option' => json_encode($arr),
	     'survey_id' => $request->input('survey_id'),
	     'type' => $request->input('type')]
	);
	
	}else {
	$save = DB::table('questions')->insert(
	    ['question' => $request->input('question'),
	     'frm_option' => '',
	     'survey_id' => $request->input('survey_id'),
	     'type' => $request->input('type')]
	);
	
	}
		if($save){
			return response()->json(['status' => 'Successful','message' => 'Question added successfully']);
		}else {
		 return response()->json(['status' => 'Failed','message' => 'Question not added']);
		 }
        }
        

     /**
     * update question
     *
     * @return response()
     */
    public function updateQuestion(Request $request,$id)
    {

     if($request->input('type') != 'textfield_s'){
       $update = DB::table('questions')->where('id', $id)->update([
       	'question' => $request->input('question'),
	     'survey_id' => $request->input('survey_id'),
	     'type' => $request->input('type'),
	     'frm_option' => '']);
       }
       else {
          $update = DB::table('questions')->where('id', $id)->update([
               'question' => $request->input('question'),
             'survey_id' => $request->input('survey_id'),
             'type' => $request->input('type'),
             'frm_option' => $request->input('frm_option')]);
       }
        if ($update){
         return response()->json(['status' => 'Successful','message' => 'Survey updated successfully']);
		}else {
		 return response()->json(['status' => 'Failed','message' => 'Survey was not updated']);
		 }
         
    }

    /**
     * answer question
     *
     * @return response()
     */
    public function answerQuestion(Request $request)
    {

          // foreach($request->all() as $key => $value) 
          // {

          // }

          return $request->all();
         
    }
    /**
     * delete question
     *
     * @return response()
     */
    public function deleteQuestion(Request $request)
    {
       $delete =  DB::table('questions')->delete($request->input('question_id'));
  
        if ($delete){
         return response()->json(['status' => 'Successful','message' => 'Question deleted successfully']);
		}else {
		 return response()->json(['status' => 'Failed','message' => 'Question was not deleted']);
		 }
         
    }
    
}
