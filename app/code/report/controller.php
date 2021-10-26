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
			self::renderLink($response['link_token']);

			// TODO
			// Initialize Link to get public_token
			// https://plaid.com/docs/link/

			// TODO
			// Exchange public_token for access_token

			// TODO
			// Get accounts
			// https://plaid.com/docs/quickstart/#making-api-requests


        }
    }

	static function renderLink($link_token)
	{
		?>
		<button id="link-button">Link Account</button>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
		<script type="text/javascript">
		(async function($) {
		var handler = Plaid.create({
			// Create a new link_token to initialize Link
		token: '<?php echo $link_token ?>',
			onLoad: function() {
			// Optional, called when Link loads
			},
			onSuccess: function(public_token, metadata) {
			// Send the public_token to your app server.
			// The metadata object contains info about the institution the
			// user selected and the account ID or IDs, if the
			// Account Select view is enabled.
			$.post('/exchange_public_token', {
				public_token: public_token,
			});
			},
			onExit: function(err, metadata) {
			// The user exited the Link flow.
			if (err != null) {
				// The user encountered a Plaid API error prior to exiting.
			}
			// metadata contains information about the institution
			// that the user selected and the most recent API request IDs.
			// Storing this information can be helpful for support.
			},
			onEvent: function(eventName, metadata) {
			// Optionally capture Link flow events, streamed through
			// this callback as your users connect an Item to Plaid.
			// For example:
			// eventName = "TRANSITION_VIEW"
			// metadata  = {
			//   link_session_id: "123-abc",
			//   mfa_type:        "questions",
			//   timestamp:       "2017-09-14T14:42:19.350Z",
			//   view_name:       "MFA",
			// }
			}
		});

		$('#link-button').on('click', function(e) {
			handler.open();
		});
		})(jQuery);
		</script>
		<?php
	}

}
Report_Controller::route();
