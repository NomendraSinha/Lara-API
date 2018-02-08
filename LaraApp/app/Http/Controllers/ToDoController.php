<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

class ToDoController extends Controller
{
    public function index()
    {
    	try {
    		$client = new GuzzleHttpClient;
		    $response = $client->post('http://localhost:8000/oauth/token', [
		        'form_params' => [
		            'client_id' => 2,
		            // The secret generated when you ran: php artisan passport:install
		            'client_secret' => 'wuSbPCgPaxPrVcv9fdN5EpviZgxlVB1LQ57G1GEA',
		            'grant_type' => 'password',
		            'username' => 'nomendra@swarepro.com',
		            'password' => 'secret',
		            'scope' => '*',
		        ]
		    ]);

		    // You'd typically save this payload in the session
		    $auth = json_decode( (string) $response->getBody() );

		    $response = $client->get('http://localhost:8000/api/todos', [
		        'headers' => [
		            'Authorization' => 'Bearer '.$auth->access_token,
		        ]
		    ]);

		    $todos = json_decode( (string) $response->getBody() );

		    $todoList = "";
		    foreach ($todos as $todo) {
		        $todoList .= "<li>{$todo->task}".($todo->done ? '✅' : '')."</li>";
		    }

		    echo "<ul>{$todoList}</ul>";

		} catch (GuzzleHttp\Exception\RequestException $e) {
		    echo $e;
		}
    }
}
