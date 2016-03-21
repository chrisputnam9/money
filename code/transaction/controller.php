<?php
namespace MCPI;

/**
 * Transaction Controller
 */
class Transaction_Controller extends Core_Controller_Abstract
{
    static public function route()
    {
        $request = self::getRequest();
        if ($request->index(0,'transaction'))
        {
            $response = self::getResponse();

            // Image Form?
            if ($request->index(1,'image'))
            {
                if ($request->file('image'))
                    self::processImage($request->file('image'), $response);
                else
                    $response->body_template = 'transaction_image';
            }

            // Standard Form?
            if ($request->index(1,'form'))
            {
                if ($request->post())
                    self::processForm($request, $response);
                else
                {
                    $response->body_data = [
                        // Will change if editing
                        'form_title' => 'New Transaction',
                        'date' => date('Y-m-d'),
                        'image' => $request->get('image'),
                    ];

                    $response->body_template = 'transaction_form';
                }
            }

            $response->finalize();
        }
    }

    // Process image submission
    static public function processImage($image, $response)
    {
        // TODO Make extensions configurable
        if (!preg_match('/\.(png|jpe?g|gif)$/', $image['name']))
            die('Image must be png, jpg or gif.  Go back and try again.');

        $filename = strtolower($image['name']);
        $filename = preg_replace('/[^\w-_.]+/', '-', $filename);
        $filename = date('Ymd-His_') . $filename;

        $dir = DIR_UPLOAD . 'transaction';
        if (!is_dir($dir))
            mkdir($dir);

        $path = $dir . DS . $filename;

        move_uploaded_file( $image['tmp_name'], $path );

        $response->redirect('/transaction/form', ['image'=>$filename]);
    }
}
Transaction_Controller::route();
