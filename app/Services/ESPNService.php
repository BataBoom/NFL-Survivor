<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

date_default_timezone_set('America/New_York');
class ESPNService
{


    private $baseURL;
    protected $client;


    public function __construct()
    {
    	$this->baseURL = 'https://site.api.espn.com/apis/site/v2/sports/';
        $this->client = new \GuzzleHttp\Client();
    }

    public function baseball2()
    {
    $res = $this->client->request('GET', $this->baseURL . 'baseball/mlb/scoreboard');

    if ($res->getStatusCode() === 200) {
        $fetch = $res->getBody();
        $grabIt = $fetch->getContents();
        $fetchIt = json_decode($grabIt, true);

    	return $fetchIt;
    } else {
    return false;
    }
	}


	public function baseball()
    {
    $request = Http::acceptJson()->get($this->baseURL . 'baseball/mlb/scoreboard');


    if ($request->ok() && $request->json('day.date') == date('Y-m-d')) {
	$events = $request->json('events');
	foreach ($events as $k => $v) {
		$results[] = 
		[
		'Event' => $events[$k]['name'], 
		'Starts' => $events[$k]['competitions'][0]['startDate'] ?? null,
		'Tickets' => $events[$k]['competitions'][0]['tickets'] ?? null,
		'Odds' => $events[$k]['competitions'][0]['odds']  ?? null,
		'News' => $events[$k]['competitions'][0]['headlines'][0]['shortLinkText']  ?? null,
		'Weather' => $events[$k]['weather']['displayValue'] ?? null, $events[$k]['weather']['temperature'] ?? null,
        'WeatherLink' => $events[$k]['weather']['link']['href'] ?? null,
		'ConditonID' => $events[$k]['weather']['conditionId'] ?? null,
		'Status' => $events[$k]['competitions'][0]['status']['type'],

		];
	}
    } else {
    $results = false;
    }
    return $results;
	}

    
}