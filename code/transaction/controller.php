<?php
namespace MCPI;

/**
 * Transaction Controller
 */
class Transaction_Controller extends Core_Controller_Abstract
{

    static protected $transaction;

    const DIR_UPLOAD = DIR_UPLOAD . 'transaction' . DS;

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
                {
                    self::processImage($request->file('image'), $response);
                }

                $response->body_template = 'transaction_image';
            }

            // Standard Form?
            if ($request->index(1,'form'))
            {

                self::$transaction = new Transaction_Model();

                if ($request->post())
                {
                    self::processForm($request, $response);
                }

                $image = $request->get('image');
                $amount = "";
                if ($image)
                {
                    $dir = self::DIR_UPLOAD;
                    $ocr = new OCR_Model($dir . $image);
                    $dollars = $ocr->getDollars();
                    if (!empty($dollars))
                        $amount = max($dollars);
                }

                $body_data = [
                    // Will change if editing
                    'form_title' => 'New Transaction',
                    'date' => date('Y-m-d'),
                    'image' => $image,
                    'amount' => $amount,
                ];

                $body_data = array_merge($body_data, self::$transaction->getOptions());

                $response->body_data = $body_data;
                $response->body_template = 'transaction_form';

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

        $dir = self::DIR_UPLOAD;
        if (!is_dir($dir))
            mkdir($dir);

        $path = $dir . $filename;

        move_uploaded_file( $image['tmp_name'], $path );

        $response->redirect('/transaction/form', ['image'=>$filename]);
    }

    // Process main form submission
    static public function processForm($request, $response)
    {
        echo("<pre>".print_r($_POST,true)."</pre>");
    }
}
Transaction_Controller::route();
