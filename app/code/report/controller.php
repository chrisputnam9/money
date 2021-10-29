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
				// Good for 30 minutes
				$public_token = $request->post('public_token');
				$metadata_json = htmlspecialchars_decode($request->post('metadata'));

				if (empty($public_token)) {
					die('Missing public_token');
				}

				if (empty($metadata_json)) {
					die('Missing metadata');
				}


				echo "<pre>";

				echo("public_token: " . $public_token);
				echo "\n";

				$metadata = json_decode($metadata_json, true);
				if (empty($metadata)) {
					echo('Invalid metadata:<br>' . $metadata_json);
					die('JSON Error: ' . json_last_error_msg());
				}

				echo("\nmetadata: ");
				print_r($metadata);

				// Exchange public_token for access_token
				// https://plaid.com/docs/api/tokens/#itempublic_tokenexchange
				$response = self::postJSON(PLAID_API_URL . "/item/public_token/exchange", [
					'client_id' => PLAID_API_CLIENT_ID,
					'secret' => PLAID_API_SECRET,
					'public_token' => $public_token
				]);
				echo("\nexchange response: ");
				print_r($response);

				$access_token = $response['access_token'];

				// Example request - get accounts with this item
				// https://plaid.com/docs/api/accounts/#accountsget
				$response = self::postJSON(PLAID_API_URL . "/accounts/get", [
					'client_id' => PLAID_API_CLIENT_ID,
					'secret' => PLAID_API_SECRET,
					'access_token' => $access_token
				]);
				echo("\naccounts response: ");
				print_r($response);

				// TODO
				// Save access_token, item_id, exchange request_id and metadata_json into database - plaid_items

				echo "</pre>";
			}
			else
			{
				// Get link_token
				$response = self::postJSON(PLAID_API_URL . "/link/token/create", [
					'client_id' => PLAID_API_CLIENT_ID,
					'secret' => PLAID_API_SECRET,
					'client_name' => 'CMP Money',
					'user' => [
						'client_user_id' => $current_user_id,
					],
					'products' => ['auth'],
					'country_codes' => ['US'],
					'language' => 'en',
				]);

				// Initialize Link to get public_token
				self::renderLink($response['link_token']);
			}

			// TODO
			// Get accounts
			// https://plaid.com/docs/quickstart/#making-api-requests

        }
    }

	static function renderLink($link_token)
	{
		?>
		<button id="add_account_button" class='btn'>Add New Account</button>
		<form id="add_account_form" action="/report/add_account" method="post">
			<input type="hidden" id="public_token" name="public_token" value="" />
			<input type="hidden" id="metadata" name="metadata" value="" />
		</form>
		<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
		<script type="text/javascript">
			const handler = Plaid.create({
				token: '<?php echo $link_token ?>',
				onSuccess: function(public_token, metadata) {
					document.getElementById('public_token').value = public_token;
					document.getElementById('metadata').value = JSON.stringify(metadata);
					document.getElementById('add_account_form').submit();
				},
				onExit: function(err, metadata) {
					// The user exited the Link flow.
					if (err != null) {
						// The user encountered a Plaid API error prior to exiting.
						alert("Issue with Plaid? See Console");
						console.group("Plaid Error");
							console.error(err);
							console.log(metadata);
						console.groupEnd();
					}
				}
			});

			document.getElementById('add_account_button').addEventListener('click', function(e) {
				handler.open();
			});
		</script>
		<?php
	}

}
Report_Controller::route();
