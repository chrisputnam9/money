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
                        $body_data['form_title'] = 'Edit Transaction';
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

                // TODO refactor so this query doesn't have to happen twice:
                //   eg. pass through below or cache on account model
                $account_options = Account_Model::getGroupedAccounts();

                // OCR if applicable
                $image = empty($body_data['image']) ? false : $body_data['image'];
                if ($image)
                {
                    $dir = self::DIR_UPLOAD;
                    // Load from cache (ran on original image)
                    $ocr = new OCR_Model($dir . $image, true);
                    $ocr_text = join("\n", $ocr->getText());
                    $body_data['ocr-text'] = $ocr_text;

                    if (empty($body_data['amount']))
                    {
                        $dollars = $ocr->getDollars();
                        if (!empty($dollars))
                            $body_data['amount'] = max($dollars);
                    }

                    // Use the most recent valid date found (if any)
                    if (empty($body_data['date_occurred']))
                    {
                        $dates = $ocr->getDates();
                        $most_recent = 0;
                        $now = time();
                        if (!empty($dates))
                        {
                            foreach ($dates as $date)
                            {
                                $time = strtotime($date);
                                // Going for most recent time, but can't be future (TODO configurable?)
                                if (false !== $time and $time > $most_recent and $time <= $now)
                                {
                                    $most_recent = $time;
                                }
                            }
                        }
                        if ($most_recent)
                        {
                            $body_data['date_occurred'] = date('Y-m-d', $most_recent);
                        }
                    }

                    // Account From
                    // Use first valid account found (if any)
                    if (empty($body_data['account_from']))
                    {
                        $account_numbers = $ocr->getPattern('/[^\/\d](\d{4})($|\D)/', 1);
                        if (!empty($account_numbers))
                        {
                            $account_map = Account_Model::getNumberMap();
                            if (!empty($account_map))
                            {
                                foreach ($account_numbers as $number)
                                {
                                    if (isset($account_map[$number]))
                                    {
                                        $body_data['account_from'] = $account_map[$number]['id'];
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    // Check for valid account names which could indicate
                    //  the transaction recipient
                    if (empty($body_data['account_to']))
                    {
                        $lower_text = strtolower($ocr_text);
                        foreach($account_options[2]['options'] as $option)
                        {
                            $name = strtolower($option['title']);
                            if (strpos($lower_text, $name) !== false)
                            {
                                $body_data['account_to'] = $option['id'];
                            }
                        }
                    }
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

            // Delete?
            if ($request->index(1,'delete'))
            {

                $id = $request->get('id');
                if ($id)
                {
                    // Allow muliple
                    $id = explode(",", $id);
                    Transaction_Model::delete($id);
                }

                // Back to index
                $response->redirect();
            }

            $response->finalize();
        }
    }

    // Process image submission
    static public function processImage($image, $response)
    {
        // TODO Make extensions configurable
        $filename = strtolower($image['name']);
        if (!preg_match('/\.(png|jpe?g|gif)$/', $filename))
            die('Image must be png, jpg or gif.  Go back and try again.');

        $filename = preg_replace('/[^\w-_.]+/', '-', $filename);
        $filename = date('Ymd-His_') . $filename;

        $dir = self::DIR_UPLOAD;
        if (!is_dir($dir))
            mkdir($dir);

        $path = $dir . $filename;

        move_uploaded_file( $image['tmp_name'], $path );

        // Run OCR and cache for later
        $ocr = new OCR_Model($path, true);
        $ocr->getText();

        $dest_filename = preg_replace('/\.\w+$/', '.png', $filename);
        $destination = $dir . $dest_filename;

        self::shrinkImage($path, $destination);

        unlink($path);

        $response->redirect('/transaction/form', ['image'=>$dest_filename]);
    }

    // Shrink an image
    static function shrinkImage($source, $destination, $unlink_invalid=true) {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg')
            $image = \imagecreatefromjpeg($source);
        elseif ($info['mime'] == 'image/gif')
            $image = \imagecreatefromgif($source);
        elseif ($info['mime'] == 'image/png')
            $image = \imagecreatefrompng($source);
        else
        {
            if ($unlink_invalid)
            {
                unlink($source);
                die('Image must be png, jpg or gif.  Go back and try again.');
            }
            else
                return false;
        }

        // Size to width 500px
        $width = $info[0];
        $height = $info[1];

        $new_width = 500;
        $new_height = 500;

        if ($width > $new_width or $height > $new_height)
        {
            // Resize whichever is higher to 500
            if ($height > $width)
                $new_width = round($width * ($new_height / $height));
            else
                $new_height = round($height * ($new_width / $width));

            $new_image = \imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            $image = $new_image;
        }

        // Convert to png
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

        $repeat = false;
        if (isset($_POST['repeat']))
        {
            $repeat = $_POST['repeat'];
            unset($_POST['repeat']);
        }


        if (Transaction_Model::save($_POST))
        {
            $id = Transaction_Model::lastInsertId();

            // Update recurrance
            if ($repeat)
            {
                Transaction_Recurrance_Controller::save($id, $repeat);
            }

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
