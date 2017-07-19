<?php

use Illuminate\Database\Seeder;

use Dwij\Laraadmin\Models\LAConfigs;

class ConfigTableSeeder extends Seeder
{

    private $data = [
        [ 'key' => "author",                'value' => "Site author", ],
        [ 'key' => "author_site",           'value' => "http://www.site_url.com", ],
        [ 'key' => "author_email",          'value' => "mail@site.com", ],
        [ 'key' => "sitename",              'value' => "Site name", ],
        [ 'key' => "sitename_part1",        'value' => "Site", ],
        [ 'key' => "sitename_part2",        'value' => "Name", ],
        [ 'key' => "sitename_short",        'value' => "Sitename", ],
        [ 'key' => "site_description",      'value' => "Enter your site description informations.", ],
        [ 'key' => "sidebar_search",        'value' => "1", ],
        [ 'key' => "show_messages",         'value' => "0", ],
        [ 'key' => "show_notifications",    'value' => "0", ],
        [ 'key' => "show_tasks",            'value' => "0", ],
        [ 'key' => "show_rightsidebar",     'value' => "0", ],
        [ 'key' => "skin",                  'value' => "skin-white", ],
        [ 'key' => "layout",                'value' => "fixed", ],
        [ 'key' => "default_email",         'value' => "mail@site.com", ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $row) {
            Configs::create([
                'key'   => $row['key'],
                'value' => $row['value'],
            ]);
        }
    }

}
