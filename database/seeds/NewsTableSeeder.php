<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('news')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('news')->insert(
            [
                [
                    'id'                    => 1,
                    'title'                 => "What if working from home could be different to how it’s been until now?",
                    'text'                  => 'I’ve been using this Distributed Work’s five levels of autonomy model since I saw it two months ago on the page of Matt Mullenweg, the founder of Automattic, the company that created the software behind a third of all websites on the web, WordPress (and the one I use for my page in Spanish since 2007).
                    The reason is obvious: besides following Matt since 2006 and holding him in the highest regard, I’m inclined to give a lot of credit to a distributed work model created by someone who thoroughly applies it to his own company, and that explains his ability to attract talent and create a highly competitive company. Automatic used to be housed in a lovely building in San Francisco until June 2017, when it decided to close it due to lack of use. If you want to know more about implementing distributed work, I would recommend reading Scott Berkun’s “The Year Without Pants”, which tells the inside story of Automattic.',
                    'datetime'              => '2020-07-06 00.00.00',
                    'image_video'           => '',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ]
            ]
        );
    }
}
