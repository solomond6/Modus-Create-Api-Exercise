<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Auth;
use Session;


class VehiclesController extends Controller
{
	public function __construct()
    {
    }
  
	public function getRequirmentOne(Request $request, $modelyear =null, $manufacturer =null, $model =null){
        
        $rating = $request->query('withRating');

        $client = new \GuzzleHttp\Client();

        try {
            if($rating == "true"){
                
                // Create a request with all parameters
                $request = $client->get('https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/'.$modelyear.'/make/'.$manufacturer.'/model/'.$model.'?format=json');
                
                //get response for the api
                $response = $request->getBody();

                $decodeResponse = json_decode($response);

                $secondResponse = [];
                foreach ($decodeResponse->Results as $key => $value) {
                    $secondRequest = $client->get('https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/'.$value->VehicleId.'?format=json');
                    $secondResponse[] = $secondRequest->getBody()->getContents();
                }

                $result = [];
                foreach ($secondResponse as $key => $value) {
                    # code...
                    $decodedValue = json_decode($value);
                    //var_dump($decodedValue->Results);exit;
                    foreach ($decodedValue->Results as $key => $dvalue) {
                        $result[] = array(
                                        'CrashRating' => $dvalue->OverallRating,
                                        'Description' => $dvalue->VehicleDescription,
                                        'VehicleId' => $dvalue->VehicleId
                                    );
                    }
                    
                }

                $finalResult = ['Count'=> sizeof($result), 'Results'=>$result];

                return json_encode($finalResult);
            
            }elseif($rating == "false"){
                
                $request = $client->get('https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/'.$modelyear.'/make/'.$manufacturer.'/model/'.$model.'?format=json');
                
                $response = $request->getBody();
                
                return $response;

            }elseif($rating != "true" || $rating != "false" || $rating == ""){

                $request = $client->get('https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/'.$modelyear.'/make/'.$manufacturer.'/model/'.$model.'?format=json');
                
                $response = $request->getBody();
                
                return $response;

            }
        }
        catch (RequestException $e) {
            // Catch all 4XX errors 
            // To catch exactly error 400 use 
            if ($e->getResponse()->getStatusCode() == '400') {
                echo json_encode(['Count'=> '0','Message'=>'Bad Request', 'Results'=>[]]);
            }
            // You can check for whatever error status code you need 

        } catch (\Exception $e) {
            // There was another exception.
            echo json_encode(['Count'=> '0','Message'=>'Bad Request', 'Results'=>[]]);
        }
    }



    public function postRequirmentTwo(Request $request){
        try{
            if($request->isMethod('post')){
                $data = $request->all();
                
                $client = new \GuzzleHttp\Client();
                
                $request = $client->get('https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/'.$data['modelYear'].'/make/'.$data['manufacturer'].'/model/'.$data['model'].'?format=json');

                // Get the actual response without headers
                $response = $request->getBody();
                
                return $response;
                
            }
        }
        catch (RequestException $e) {
            // Catch all 4XX errors 
            // To catch exactly error 400 use 
            if ($e->getResponse()->getStatusCode() == '400') {
                echo json_encode(['Count'=> '0','Message'=>'Bad Request', 'Results'=>[]]);
            }
            // You can check for whatever error status code you need 

        } catch (\Exception $e) {
            // There was another exception.
            echo json_encode(['Count'=> '0','Message'=>'Bad Request', 'Results'=>[]]);
        }
    }
}
