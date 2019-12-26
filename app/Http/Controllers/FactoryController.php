<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use \Vision as vision;

/**
 * Class HomeController.
 */
class FactoryController extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('analyze');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function post(Request $request)
    {

        if ($request->file('image'))
        {
            $image = $request->file('image');
            $b64image = base64_encode(file_get_contents($image));
        }
        else dd('Error!');

        $req = '{
				  "requests":[
					{
					  "image":{
						"content":"@content"
					  },
					  "features": [
						{
						  "maxResults": 50,
						  "type": "LANDMARK_DETECTION"
						},
						{
						  "maxResults": 50,
						  "type": "FACE_DETECTION"
						},
						{
						  "maxResults": 50,
						  "type": "OBJECT_LOCALIZATION"
						},
						{
						  "maxResults": 50,
						  "type": "LOGO_DETECTION"
						},
						{
						  "maxResults": 50,
						  "type": "LABEL_DETECTION"
						},
						{
						  "maxResults": 50,
						  "type": "DOCUMENT_TEXT_DETECTION"
						},
						{
						  "maxResults": 50,
						  "type": "SAFE_SEARCH_DETECTION"
						},
						{
						  "maxResults": 50,
						  "type": "IMAGE_PROPERTIES"
						},
						{
						  "maxResults": 50,
						  "type": "CROP_HINTS"
						},
						{
						  "maxResults": 50,
						  "type": "WEB_DETECTION"
						}
					  ],
					  "imageContext": {
						"cropHintsParams": {
						  "aspectRatios": [
							0.8,
							1,
							1.2
						  ]
						}
					  }
					}
				  ]
				}';

        $req = str_replace('@content', $b64image, $req);

        $client = new \GuzzleHttp\Client();
        try
        {
            $res = $client->request('POST', env('GOOGLE_VISION_API_ENDPOINT') .'?key='. env('GOOGLE_APP_KEY_SECRET') , ['requests' => json_decode($req)->requests[0]]);
        }
        catch(\Exception $x)
        {
            dd($x->getMessage());
        }
        $data['ai'] = collect(json_decode($res->getBody()));
        $data['id'] = $b64image;
        $data['exclude'] = ['boundingPoly', 'detectionConfidence', 'boundingPoly', 'fdBoundingPoly', 'landmarks', 'panAngle', 'rollAngle', 'tiltAngle', 'landmarkingConfidence', ];

        return view('results', $data);
    }
}

