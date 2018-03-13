<?php

use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory('App\User')->create(['email' => 'test@mail.com', 'password' => bcrypt("azerty")]);
        $urls = factory('App\Url', 25)->create(['user_id' => $user->id]);
        foreach ($urls as $url) {
            $visitCount = rand(0, 150);
            for ($i = 0; $i < $visitCount; $i++) {
                if ($i < ($visitCount / 4)) {
                    $date = Carbon::now()->subHours(rand(0, 24));
                } else if ($i < ($visitCount / 2)) {
                    $date = Carbon::now()->subDays(rand(1, 6));
                } else if ($i < ($visitCount * 3 / 4)) {
                    $date = Carbon::now()->subDays(rand(7, 30));
                } else {
                    $date = Carbon::now()->subMonths(rand(1, 12));
                }
                $url->visits()->create([
                    'country' => $this->randomCountry(), 
                    'referrer' => $this->randomRef(),
                    'created_at' => $date
                ]);       
            }
        }
    }
    
    protected function randomCountry()
    {
        $countries = ['France' , 'England', 'Spain', 'Italia', 'Russia', 'Ireland', null];
        $index = array_rand($countries);
        return $countries[$index];
    }
    
    protected function randomRef()
    {
        $refs = ['Twitter', 'Facebook', 'LinkedIn', null, null, null, null, null];
        $index = array_rand($refs);
        return $refs[$index]; 
    }
}
