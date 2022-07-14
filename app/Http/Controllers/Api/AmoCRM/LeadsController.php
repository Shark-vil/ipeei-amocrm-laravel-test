<?php

namespace App\Http\Controllers\Api\AmoCRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ClientACRM;

class LeadsController extends Controller
{
	private $client;

	public function __construct()
	{
		$this->client = new ClientACRM();
	}

  public function get_lead_by_id(int $id)
	{
		$entry = $this->client->getLeadById($id);
		var_dump($entry);
	}
}
