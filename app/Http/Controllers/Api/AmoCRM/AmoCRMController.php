<?php

namespace App\Http\Controllers\Api\AmoCRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ClientACRM;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use App\Models\AcrmClient;
use Carbon\Carbon;

class AmoCRMController extends Controller
{
	/**
	 * /api/amocrm/
	 */
	public function index(Request $request)
	{
		$acrmService = new ClientACRM();
		$apiClient = $acrmService->client();

		if ($request->has('referer')) {
			$apiClient->setAccountBaseDomain($request->input('referer'));
		}

		if (!$request->has('code')) {
			$state = bin2hex(random_bytes(16));
			$authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
				'state' => $state,
				'mode' => 'post_message',
			]);

			return redirect($authorizationUrl);
		}

		try {
			$accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($request->input('code'));

			if (!$accessToken->hasExpired()) {
				$token = $accessToken->getToken();
				$refreshToken = $accessToken->getRefreshToken();
				$expires = $accessToken->getExpires();
				$baseDomain = $apiClient->getAccountBaseDomain();

				$entry = AcrmClient::where('id', 1)->first();

				if (!$entry) {
					$entry = AcrmClient::create([
						'accessToken' => $token,
						'refreshToken' => $refreshToken,
						'expires' => $expires,
						'baseDomain' => $baseDomain,
					]);
				} else {
					if ($entry->expires > Carbon::now()->timestamp) {
						return response()->json([
							'error' => 'You cannot override an active token.'
						], 400);
					}

					$entry->accessToken = $token;
					$entry->refreshToken = $refreshToken;
					$entry->expires = $expires;
					$entry->baseDomain = $baseDomain;
					$entry->save();
				}
			}

			/** @var ResourceOwnerInterface */
			$ownerDetails = $apiClient->getOAuthClient()->getResourceOwner($accessToken);
			var_dump($ownerDetails);
		} catch (\Exception $e) {
			return response()->json([
				'error' => (string)$e
			], 400);
		}
	}
}
