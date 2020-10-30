<?php

use Illuminate\Database\Seeder;
use App\Models\Method;

class MethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = [
        	"Workshop/Self Learning",
        	"Sharing Practice/Professional’s Talks",
        	"Discussion Room",
        	"Coaching",
        	"Mentoring",
        	"Job Assignment"
        ];

        foreach ($methods as $item) {
        	Method::create(['name' => $item]);
        }
    }
}
