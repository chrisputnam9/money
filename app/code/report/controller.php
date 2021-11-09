<?php
namespace MCPI;

use Exception;

/**
 * Report Controller
 */
class Report_Controller extends Core_Controller_Abstract
{

    static public function route()
    {
        $request = self::getRequest();

        if ($request->index(0,'report'))
        {

			echo "<h1>Report Testing</h1>";

			$current_user = Login_Helper::getCurrentUser();
			$current_user_id = "cmp_money_" . $current_user['id'];

			if ($request->index(1,'add_account'))
			{
				die("<pre>".print_r($_REQUEST,true)."</pre>");
			}

			echo "<h2>Current User</h2>";
			echo("<pre>".print_r($current_user,true)."</pre>");

			if ($request->index(1,'add_account'))
			{
				die('add_account');
			}
			else
			{
				// Authenticate
				$response = self::postJSON(
					FINICITY_API_URL . "/aggregation/v2/partners/authentication",
					[
						'partnerId' => FINICITY_API_PARTNER_ID,
						'partnerSecret' => FINICITY_API_PARTNER_SECRET,
					],
					[
						'Finicity-App-Key: ' . FINICITY_API_APP_KEY,
					]
				);

				$token = $response['token'];
			}

			echo "Token: " . $token . "<br>";

			/*
			echo "Add Customer:<br>";
			$response = self::postJSON(
					FINICITY_API_URL . "/aggregation/v2/customers/active", // active - testing
					[
						'username' => $current_user['name'] . '_' . $current_user['id'],
					],
					[
						'Finicity-App-Token: ' . $token,
						'Finicity-App-Key: ' . FINICITY_API_APP_KEY,
					]
			);
			echo("<pre>".print_r($response,true)."</pre>");
			 */

			/*
			echo "Customers:<br>";
			// Get Customers
			$response = self::getJSON(
					FINICITY_API_URL . "/aggregation/v1/customers",
					[
						// See https://api-reference.finicity.com/#/rest/api-endpoints/customer/get-customers
					],
					[
						'Finicity-App-Token: ' . $token,
						'Finicity-App-Key: ' . FINICITY_API_APP_KEY,
					]
			);
			echo("<pre>".print_r($response,true)."</pre>");
			 */
			$customer_id = 5017068539;

			/*
			// Get Institutions - search
			// https://api-reference.finicity.com/#/rest/api-endpoints/institutions/get-institutions
			echo "Institutions:";
			$response = self::getJSON(
					FINICITY_API_URL . "/institution/v2/institutions",
					[
						'search' => 'pnc'
					],
					[
						'Finicity-App-Token: ' . $token,
						'Finicity-App-Key: ' . FINICITY_API_APP_KEY,
					]
			);
			echo("<pre>".print_r($response,true)."</pre>");
			 */
			$pnc_bank = 2866;
			
			// Try Connect lite with specific institution
			// todo

			// Get Connect URL
			$response = self::postJSON(
					FINICITY_API_URL . "/connect/v2/generate/lite",
					[
						'partnerId' => FINICITY_API_PARTNER_ID,
						'customerId' => $customer_id,
						'institutionId' => $pnc_bank,
						'redirectUri' => 'https://money-dev.chrisputnam.info/report/add_account',

					],
					[
						'Finicity-App-Token: ' . $token,
						'Finicity-App-Key: ' . FINICITY_API_APP_KEY,
					]
			);
			echo("<pre>".print_r($response,true)."</pre>");

        }
    }

}
Report_Controller::route();
