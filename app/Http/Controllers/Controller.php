<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use \Vision as vision;

/**
 * Class HomeController.
 */
class Controller extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }


    /**
     * @return \Illuminate\View\View
     */
    public function post(Request $request)
    {
        $vision = new \Vision\Vision(
            env('GOOGLE_APP_KEY_SECRET'),
            [
                // See a list of all features in the table below
                // Feature, Limit
                new \Vision\Feature(\Vision\Feature::FACE_DETECTION, 100),
            ]
        );

        try {

            //@ Todo Validate file as well --
            $imagePath = $_FILES['file']['file'];
            $response = $vision->request(

                new \Vision\Request\Image\LocalImage($imagePath)
            );

            $faces = $response->getFaceAnnotations();
            foreach ($faces as $face) {
                foreach ($face->getBoundingPoly()->getVertices() as $vertex) {
                    echo sprintf('Person at position X %f and Y %f', $vertex->getX(), $vertex->getY());
                }
            }

            $vision = new \Vision\Vision(
                env('GOOGLE_APP_KEY_SECRET'),
                [
                    // Feature, Limit
                    new \Vision\Feature(\Vision\Feature::IMAGE_PROPERTIES, 50),
                ]
            );

            $request = new \GuzzleHttp\Psr7\Request('GET', env('GOOGLE_VISION_API_ENDPOINT'));
            $res = $request->request('FETCH', [
                'auth' => ['token', env('GOOGLE_APP_KEY_SECRET')]
            ]);

            $promise = $request->sendAsync($request)->then(function ($response) use ($res) {
                $response = json_decode($res->getBody());
            });

            $imgAnnotations = $promise->wait(function ($imgRemote) {
                return $imgRemote['labelAnnotations'];
            });

            function values($vertex)
            {
                foreach ($vertex as $vertexTuple) {
                    foreach ($vertexTuple->getBoundingPoly()->getVertices() as $vertex) {
                        yield [
                            $vertex->cropHintsAnnotation,
                            $vertex->imagePropertiesAnnotation,
                            $vertex->labelAnnotations,
                            $vertex->localizedObjectAnnotations,
                            $vertex->webDetection,
                        ];
                    }
                }
            }

            $response = $vision->request(
                new \Vision\Request\Image\RemoteImage($imgAnnotations)
            );

            $props = $response->getImagePropertiesAnnotation();
            $data_annotate = array_map(array('values', $response), ( array )$props);
            $data['props'] = collect($data_annotate);

            return view('analyze', compact($data));
        }

        catch(\Exception $exception){
            return dd('resp_err');
        }
    }
}
