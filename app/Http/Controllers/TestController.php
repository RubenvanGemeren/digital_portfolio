<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return [
            ['id' => 1,'name' => "Project #1", 'description' => "Dit is het eerste project", 'tags' => ['school', 'work', 'web-development'], 'langs' => ['php']],
            ['id' => 2,'name' => "Project #2", 'description' => "Dit is het tweede project", 'tags' => ['school', 'fun', 'public transport'], 'langs' => ['php']],
            ['id' => 3,'name' => "Project #3", 'description' => "Dit is het tweede project", 'tags' => ['school', 'fun', 'public transport'], 'langs' => ['php']],
            ['id' => 4,'name' => "Project #4", 'description' => "Dit is het tweede project", 'tags' => ['school', 'fun', 'public transport'], 'langs' => ['php']],
            ['id' => 5,'name' => "Project #5", 'description' => "Dit is het tweede project", 'tags' => ['school', 'fun', 'public transport'], 'langs' => ['php']],
        ];
    }
}
