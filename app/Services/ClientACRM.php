<?php

namespace App\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Models\LeadModel;

class ClientACRM
{
	 /** @var AmoCRMApiClient */
	private $apiClient;

	 /** @var string */
	private $clientId;

	 /** @var string */
	private $clientSecret;

	 /** @var string */
	private $redirectUri;

  public function __construct()
	{
		$this->clientId = env('ACRM_ID');
		$this->clientSecret = env('ACRM_SECRET');
		$this->redirectUri = env('ACRM_REDIRECT');
		$this->apiClient = new AmoCRMApiClient($this->clientId, $this->clientSecret, $this->redirectUri);
	}

	public function client()
	{
		return $this->apiClient;
	}

	public function getLeadById(int $id) : LeadModel
	{
		return $this->apiClient->leads()->getOne($id);
	}
}
