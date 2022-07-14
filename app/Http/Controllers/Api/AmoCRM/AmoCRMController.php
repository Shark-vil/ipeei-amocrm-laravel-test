<?php

namespace App\Http\Controllers\Api\AmoCRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ClientACRM;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

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
				return response()->json([
					'accessToken' => $accessToken->getToken(),
					'refreshToken' => $accessToken->getRefreshToken(),
					'expires' => $accessToken->getExpires(),
					'baseDomain' => $apiClient->getAccountBaseDomain(),
				]);
			}
		} catch (\Exception $e) {
			die((string)$e);
		}

		/** @var ResourceOwnerInterface */
		$ownerDetails = $apiClient->getOAuthClient()->getResourceOwner($accessToken);
		var_dump($ownerDetails);
	}
}
