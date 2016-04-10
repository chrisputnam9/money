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
                $amount = $request->post('amount');
                if ($image and empty($amount))
                {
                    $dir = self::DIR_UPLOAD;
                    $ocr = new OCR_Model($dir . $image);
                    $dollars = $ocr->getDollars();
                    if (!empty($dollars))
                        $amount = max($dollars);
                }

                $date = $request->post('date_occurred');
                if (empty($date))
                    $date = date('Y-m-d');

                $body_data = [
                    // Will change if editing
                    'form_title' => 'New Transaction',

                    'amount' => $amount,
                    'category' => $request->post('category'),
                    'account_from' => $request->post('account_from'),
                    'account_from_other' => $request->post('account_from_other'),
                    'account_to' => $request->post('account_to'),
                    'account_to_other' => $request->post('account_to_other'),
                    'date_occurred' => $date,
                    'classification' => $request->post('classification'),
                    'notes' => $request->post('notes'),
                    'image' => $image,
                ];

                $options = Transaction_Model::getOptions();

                $body_data = array_merge($body_data, $options);

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
        if (empty($_POST['account_from']) and !empty($_POST['account_from_other']))
        {
            Account_Model::create([
                'title' => $_POST['account_from_other'],
                'classification' => Account_Model::OTHER
            ]);
            $_POST['account_from'] = Account_Model::lastInsertId();
        }

        if (empty($_POST['account_to']) and !empty($_POST['account_to_other']))
        {
            Account_Model::create([
                'title'=>$_POST['account_to_other'],
                'classification' => Account_Model::OTHER
            ]);
            $_POST['account_to'] = Account_Model::lastInsertId();
        }

        $submit = $_POST['submit'];

        unset($_POST['account_from_other']);
        unset($_POST['account_to_other']);
        unset($_POST['submit']);

        Transaction_Model::save($_POST);
        $id = Transaction_Model::lastInsertId();

        // Redirect based on selection
        echo("<pre>".print_r($submit,true)."</pre>");
        die("<pre>".print_r($_POST,true)."</pre>");
    }
}
Transaction_Controller::route();
