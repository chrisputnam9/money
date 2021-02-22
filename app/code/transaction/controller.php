<?php
namespace MCPI;

use Exception;

/**
 * Transaction Controller
 */
class Transaction_Controller extends Core_Controller_Abstract
{

    const DIR_UPLOAD = DIR_UPLOAD . 'transaction' . DS;
    const DIR_FILE = DIR_TMP . 'file' . DS;

    static public function route()
    {
        $request = self::getRequest();
        $response = self::getResponse();
        $budget_menu = self::getBudgetMenu();

        if ($request->index(0,'transaction'))
        {
            $transaction_model = new Transaction_Model();
            $response->main_data['show_menu'] = true;

            // List
            if (empty($request->index(1)) or $request->index(1,'list'))
            {
                $response->menu['transaction']['class'] = 'active';
                $response->main_data['show_transaction_buttons'] = true;
                self::getDateFilter()->enable();
                self::getBudgetMenu()->enable();

                $response->body_template = 'transaction_list';
                $response->body_data = [
                    'category' => $transaction_model->getCategory(),
                    'show_all_url' => $request->url(null, ['category' => null]),
                    'transactions' => array_values($transaction_model->getListing()),
                ];
            }

            // Image Form?
            if ($request->index(1,'image'))
            {
                if ($request->file('image'))
                {
                    self::processImage($request->file('image'), $response);
                }

                $response->body_template = 'transaction_image';
            }

            // Text Form?
            if ($request->index(1,'text'))
            {
                $extra_data = [];
                $text = false;
                $action = 'render';

                if ($request->post('text'))
                {
                    $text = $request->post('text');
                }
                else
                {
                    $data = $request->body;
                    $page_title = $data['page_title'];
                    $page_url = $data['page_url'];
                    $text = $data['selection'];

                    $extra_data['notes'] = "Page:\n$page_title\n\n";
                    $extra_data['notes'].= "URL:\n$page_url";

                    if (!empty($data['app_window']))
                    {
                        $extra_data['app_window'] = 1;
                    }
                }

                if (!empty($text))
                {
                    self::processText($text, $extra_data, $response);
                }

                $response->body_template = 'transaction_text';
            }

            // Standard Form?
            if ($request->index(1,'form'))
            {

                $body_data["are_duplicates"] = "";

                // posted data? try to save
                if ($request->post())
                {
                    $body_data["duplicates"] = self::processForm($request, $response);
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
                    if (empty($db_data[$id]))
                    {
                        $response->fail("Transaction id '$id' not found.", '404');
                    }
                    else
                    {
                        $body_data['form_title'] = 'Edit Transaction';
                        $body_data = array_merge($body_data, $db_data[$id]);
                        $body_data['repeat'] = Transaction_Recurrance_Controller::getFormData($id);
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
                $file = empty($body_data['file']) ? false : $body_data['file'];

                $image_or_file = $image ? $image : $file;
                $dir = $image ? self::DIR_UPLOAD : self::DIR_FILE;

                $body_data['amount_options'] = [];
                if (!empty($body_data['amount']))
                {
                    $amount = number_format((float) $body_data['amount'], 2, '.', '');
                    $body_data['amount_options'][$amount]= [
                        'amount' => $amount,
                    ];
                }

                if ($image_or_file)
                {
                    // Load from cache (ran on original image)
                    $ocr = new OCR_Model(
                        $image ? ($dir . $image) : false,
                        $file ? ($dir . $file) : false
                    );
                    $ocr_text = join(EOL, $ocr->getText());
                    $body_data['ocr-text'] = $ocr_text;

                    if (empty($body_data['amount']))
                    {
                        $dollars = $ocr->getDollars();
                        if (!empty($dollars))
                        {
                            $body_data['amount'] = number_format((float) max($dollars), 2, '.', '');

                            $dollars = array_unique($dollars);
                            array_map(function ($amount) {
                                return number_format((float) $amount, 2, '.', '');
                            }, $dollars);
                            rsort($dollars);

                            foreach ($dollars as $amount)
                            {
                                $body_data['amount_options'][$amount] = [
                                    'amount' => $amount
                                ];
                            }
                        }
                    }
                    else
                    {
                        $amount = number_format((float) $body_data['amount'], 2, '.', '');
                        $body_data['amount'] = $amount;
                        $body_data['amount_options'][$amount] = [
                            'amount' => $amount
                        ];
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
                        $account_numbers = $ocr->getPattern('[^/\d](\d{4})($|\D)', 1);
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
                            $pattern = strtolower($option['title']);
                            $pattern = preg_replace("/[^\w]+/", "\b.*\b", $pattern);
                            $pattern = "/\b$pattern\b/";
                            if (preg_match($pattern, $lower_text))
                            {
                                $body_data['account_to'] = $option['id'];
                            }
                        }
                    }
                }

                // Options always needed for template
                $options = $transaction_model->getOptions($body_data);

                $body_data = array_merge($body_data, $options);

                // Prep the date for field
                $date = empty($body_data['date_occurred']) ? false : strtotime($body_data['date_occurred']);
                $body_data['date_occurred'] = $date ? date('Y-m-d', $date) : "";

                $body_data['today_datestamp'] = date('Y-m-d');
                $body_data['yesterday_datestamp'] = date('Y-m-d', strtotime('yesterday'));

                // Debug data behind the form
                if (isset($_GET['debug'])){
                    die("<pre>".print_r($body_data,true)."</pre>");
                }

                if (!isset($body_data["duplicates"]))
                {
                    $body_data["duplicates"] = Transaction_Model::findDuplicates($body_data);
                }

                // If warned about duplicates, then we'll ignore them next time and save anyway when requested
                if (!empty($body_data["duplicates"]))
                {
                    $duplicates = $body_data["duplicates"];
                    $body_data["duplicates"] = [];
                    foreach ($duplicates as $duplicate)
                    {
                        $duplicate['date_formatted'] = date('m/d/y', strtotime($duplicate['date_occurred']));
                        $duplicate['amount_formatted'] = '$' . number_format($duplicate['amount'], 2);
                        $body_data["duplicates"][]= $duplicate;
                    }
                    $body_data["are_duplicates"] = 1;
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

                    Transaction_Recurrance_Controller::delete($id);

                    Transaction_Model::delete($id);
                }

                // Back to index or close window
                if ($request->get('app_window'))
                {
                    $response->close_window('Transaction Deleted');
                }
                $response->redirect('/transaction/list');
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
            $response->fail('Image must be png, jpg or gif.');

        if (
            !empty($image['error'])
            or empty($image['tmp_name'])
        ){
            $response->fail('Failed to upload image. May be too large. Limit is ' . ini_get('upload_max_filesize') . '.');
        }

        $filename = preg_replace('/[^\w-_.]+/', '-', $filename);
        $filename = date('Ymd-His_') . $filename;

        $dir = self::DIR_UPLOAD;
        if (!is_dir($dir))
            mkdir($dir);

        $path = $dir . $filename;

        $success = move_uploaded_file( $image['tmp_name'], $path );

        if (!$success)
            $response->fail('Failed to move uploaded file - check permissions');

        try {
            // Run OCR and cache for later
            $ocr = new OCR_Model($path);
            $ocr->getText();
        } catch (Exception $e) {
            $response->fail("Issue processing image: " . $e->getMessage());
        }

        $dest_filename = preg_replace('/\.\w+$/', '_resized.png', $filename);
        $destination = $dir . $dest_filename;

        self::shrinkImage($path, $destination);

        unlink($path);

        $response->redirect('/transaction/form', ['image'=>$dest_filename]);
    }

	// Process text submission
	static public function processText($text, $extra_data=[], $response)
    {
        // Build unique filename based on
        //  - first 10 alphanumeric characters of text
        //  - date/time
        //  - all lowercase
        $alphanumeric = preg_replace('/[^a-z0-9]+/', '', strtolower($text));
        $filename = substr($alphanumeric, 0, 50);
        $filename = date('Ymd-His_') . $filename . '.txt';

        $dir = self::DIR_FILE;
        if (!is_dir($dir))
            mkdir($dir);

        $path = $dir . $filename;

        $success = file_put_contents($path, $text);

        if ($success == false)
            die('Unable to save text to ' . $path);

        $response->redirect('/transaction/form', array_merge($extra_data, ['file'=>$filename]));
    }

    // Shrink an image
    static function shrinkImage($source, $destination, $unlink_invalid=true)
    {

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

        $new_width = 750;
        $new_height = 750;

        if ($width > $new_width or $height > $new_height)
        {
            // Resize whichever is higher to new dimension
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
        
        $data_to_save = $_POST;

        $success = true;
        $app_window = !empty($data_to_save['app_window']);
        unset($data_to_save['app_window']);
        
        // Take care of custom amount
        if (empty($data_to_save['amount']))
        {
            $data_to_save['amount'] = $data_to_save['amount_other'];
        }
        unset($data_to_save['amount_other']);

        // save a new account?
        if (empty($data_to_save['account_from']) and !empty($data_to_save['account_from_other']))
        {
            if (Account_Model::create([
                'title' => trim($data_to_save['account_from_other']),
                'classification' => Account_Model::OTHER
            ]))
            {
                $data_to_save['account_from'] = Account_Model::lastInsertId();
                unset($data_to_save['account_from_other']);
            }
            else
            {
                $success = false;
            }
        }
        else
        {
            unset($data_to_save['account_from_other']);
        }

        // save a new account?
        if (empty($data_to_save['account_to']) and !empty($data_to_save['account_to_other']))
        {
            if (Account_Model::create([
                'title' => trim($data_to_save['account_to_other']),
                'classification' => Account_Model::OTHER
            ]))
            {
                $data_to_save['account_to'] = Account_Model::lastInsertId();
                unset($data_to_save['account_to_other']);
            }
            else
            {
                $success = false;
            }
        }
        else
        {
            unset($data_to_save['account_to_other']);
        }

        $submit = $data_to_save['submit'];
        unset($data_to_save['submit']);

        $repeat = false;
        if (isset($data_to_save['repeat']))
        {
            $repeat = $data_to_save['repeat'];
            unset($data_to_save['repeat']);
        }
         
        $update_children = false;
        if (isset($data_to_save['update_recurrances']))
        {
            $update_children = ($data_to_save['update_recurrances'] == 'yes');
            unset($data_to_save['update_recurrances']);
        }

        $duplicates = Transaction_Model::findDuplicates($data_to_save);
        if (!empty($duplicates))
        {
            return $duplicates;
        }
        unset($data_to_save['ignore_duplicates']);

        if (Transaction_Model::save($data_to_save))
        {
            $id = empty($data_to_save['id']) ? Transaction_Model::lastInsertId() : $data_to_save['id'];

            // Update recurrance
            if ($repeat)
            {
                $repeat['date_start'] = $data_to_save['date_occurred'];
            }
            Transaction_Recurrance_Controller::save($id, $repeat, $update_children);
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
                    $get = ['id' => $id];
                    if ($app_window) $get['app_window'] = 1;
                    $response->redirect('/transaction/form', $get);
                    break;
                case 'save_new':
                    $get = [];
                    if ($app_window) $get['app_window'] = 1;
                    $response->redirect('/transaction/form', $get);
                    break;
                case 'save_close':
                    if ($app_window)
                    {
                        $response->close_window('Transaction Saved');
                    }
                    $response->redirect('/transaction/list');
                    break;
            }
        }
        else
        {
            if ($app_window) $_GET['app_window'] = 1;

            // Redirect so POST doesn't mess with history
            $response->redirect('transaction/form', array_merge($_GET, $_POST));
        }
    }
}
Transaction_Controller::route();
