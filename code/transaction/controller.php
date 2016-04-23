<?php
namespace MCPI;

/**
 * Transaction Controller
 */
class Transaction_Controller extends Core_Controller_Abstract
{

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

                // posted data? try to save
                if ($request->post())
                {
                    self::processForm($request, $response);
                }

                // Start with defaults
                $body_data = [
                    // Will change if editing
                    'form_title' => 'New Transaction',
                ];

                // Load from DB if applicable
                $id = $request->get('id');
                if ($id)
                {
                    $db_data = Transaction_Model::getById($id);
                    if (!empty($db_data[$id]))
                    {

                        $body_data = array_merge($body_data, $db_data[$id]);
                    }
                }

                // Merge in get data
                if ($request->get())
                {
                    $body_data = array_merge($body_data, $_GET);
                }

                // Merge in post data
                if ($request->post())
                {
                    $body_data = array_merge($body_data, $_POST);
                }

                // OCR if applicable
                $image = $request->get('image');
                $amount = $request->post('amount');
                if ($image and empty($amount))
                {
                    $dir = self::DIR_UPLOAD;
                    $ocr = new OCR_Model($dir . $image);
                    $dollars = $ocr->getDollars();
                    if (!empty($dollars))
                        $body_data['amount'] = max($dollars);
                }

                // Options always needed for template
                $options = Transaction_Model::getOptions($body_data);

                $body_data = array_merge($body_data, $options);

                // Prep the date for field
                $date = empty($body_data['date_occurred']) ? time() : strtotime($body_data['date_occurred']);
                $body_data['date_occurred'] = date('Y-m-d', $date);

                // Prep the amount
                if (!empty($body_data['amount']))
                {
                    $body_data['amount'] = number_format((float) $body_data['amount'], 2);
                }

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

        $dest_filename = preg_replace('/\.\w+$/', '.png', $path);
        $destination = $dir . $dest_filename;

        self::shrinkImage($path, $destination);

        unlink($path);

        die('done');
        $response->redirect('/transaction/form', ['image'=>$dest_filename]);
    }

    // Shrink an image
    static function shrinkImage($source, $destination) {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg')
            $image = \imagecreatefromjpeg($source);
        elseif ($info['mime'] == 'image/gif')
            $image = \imagecreatefromgif($source);
        elseif ($info['mime'] == 'image/png')
            $image = \imagecreatefrompng($source);

        // Size to width 500px
        $width = $info[0];
        $new_width = 500;
        if ($width > $new_width)
        {
            $height = $info[1];
            $new_heigth = round($height * ($new_width / $width));
            $new_image = \imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $widh, $height);
            $image = $new_image;
        }

        \imagepng($image, $destination, 9);
    }

    // Process main form submission
    static public function processForm($request, $response)
    {
        //TODO add validation
        
        $success = true;
        
        // save a new account?
        if (empty($_POST['account_from']) and !empty($_POST['account_from_other']))
        {
            if (Account_Model::create([
                'title' => $_POST['account_from_other'],
                'classification' => Account_Model::OTHER
            ]))
            {
                $_POST['account_from'] = Account_Model::lastInsertId();
                unset($_POST['account_from_other']);
            }
            else
            {
                $success = false;
            }
        }
        else
        {
            unset($_POST['account_from_other']);
        }

        // save a new account?
        if (empty($_POST['account_to']) and !empty($_POST['account_to_other']))
        {
            if (Account_Model::create([
                'title'=>$_POST['account_to_other'],
                'classification' => Account_Model::OTHER
            ]))
            {
                $_POST['account_to'] = Account_Model::lastInsertId();
                unset($_POST['account_to_other']);
            }
            else
            {
                $success = false;
            }
        }
        else
        {
            unset($_POST['account_to_other']);
        }

        $submit = $_POST['submit'];
        unset($_POST['submit']);

        if (Transaction_Model::save($_POST))
        {
            $id = Transaction_Model::lastInsertId();
        }
        else
        {
            $success = false;
        }

        // Redirect based on selection
        if ($success)
        {
            switch($submit)
            {
                case 'apply':
                    // Redirect and load from id
                    $response->redirect('/transaction/form', ['id' => $id]);
                    break;
                case 'save_new':
                    $response->redirect('/transaction/form');
                    break;
                case 'save_close':
                    $response->redirect();
                    break;
            }
        }
        else
        {
            // Redirect so POST doesn't mess with history
            $response->redirect('transaction/form', array_merge($_GET, $_POST));
        }
    }
}
Transaction_Controller::route();
