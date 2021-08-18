<?php

namespace Database\Seeders;

use App\Models\Rule;
use App\Models\Category;
use Illuminate\Database\Seeder;

class RulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'category_order' => 1,
            'category_text' => 'American Football'
        ]);

        Category::create([
            'category_order' => 2,
            'category_text' => 'Aussie Rules'
        ]);

        Category::create([
            'category_order' => 3,
            'category_text' => 'Badminton'
        ]);

        $rules = [
        [
            'category_id' => 1,
            'rule_order' => 1,
            'rule_text' => 'If any delay occurs (worsening weather conditions, darkness...) all markets remain unsettled. The betting process in this case, resumes the moment the match continues.'
        ],
        [
            'category_id' => 1,
            'rule_order' => 2,
            'rule_text' => 'Overtime is not taken into account by markets unless otherwise stated.'
        ],
        [
            'category_id' => 1,
            'rule_order' => 3,
            'rule_text' => 'If markets have an incorrect score and are still open then we keep the right to cancel betting.'
        ],
        [
            'category_id' => 1,
            'rule_order' => 4,
            'rule_text' => 'We will also void betting in cases when odds were offered with an incorrect match time (starting at 89 seconds).'
        ],
        [
            'category_id' => 1,
            'rule_order' => 5,
            'rule_text' => 'In the event that an incorrect gaming score is specified, we have the right to void bets for this entire period.'
        ],
        [
            'category_id' => 1,
            'rule_order' => 6,
            'rule_text' => 'In case of stopped or delayed matches, all markets are considered canceled unless the match continues in the same NFL weekly schedule (Thursday - Wednesday local stadium time).'
        ],
        [
            'category_id' => 2,
            'rule_order' => 1,
            'rule_text' => 'If any delay occurs (worsening weather conditions, darkness...) all markets remain unsettled. The betting process in this case, resumes the moment the match continues.'
        ],
        [
            'category_id' => 2,
            'rule_order' => 4,
            'rule_text' => 'In case of stopped or delayed matches, all markets are considered canceled unless the match continues in the same NFL weekly schedule (Thursday - Wednesday local stadium time).'
        ],
        [
            'category_id' => 2,
            'rule_order' => 5,
            'rule_text' => 'In case of stopped or delayed matches, all markets are considered canceled unless the match continues in the same NFL weekly schedule (Thursday - Wednesday local stadium time).'
        ],
        [
            'category_id' => 2,
            'rule_order' => 6,
            'rule_text' => 'In case of stopped or delayed matches, all markets are considered canceled unless the match continues in the same NFL weekly schedule (Thursday - Wednesday local stadium time).'
        ],
        [
            'category_id' => 2,
            'rule_order' => 7,
            'rule_text' => 'If any delay occurs (worsening weather conditions, darkness...) all markets remain unsettled. The betting process in this case, resumes the moment the match continues.'
        ],
        [
            'category_id' => 3,
            'rule_order' => 3,
            'rule_text' => 'In case of stopped or delayed matches, all markets are considered canceled unless the match continues in the same NFL weekly schedule (Thursday - Wednesday local stadium time).'
        ],
        [
            'category_id' => 3,
            'rule_order' => 4,
            'rule_text' => 'In case of stopped or delayed matches, all markets are considered canceled unless the match continues in the same NFL weekly schedule (Thursday - Wednesday local stadium time).'
        ],
        [
            'category_id' => 3,
            'rule_order' => 5,
            'rule_text' => 'In case of stopped or delayed matches, all markets are considered canceled unless the match continues in the same NFL weekly schedule (Thursday - Wednesday local stadium time).'
        ],
        [
            'category_id' => 3,
            'rule_order' => 6,
            'rule_text' => 'In case of stopped or delayed matches, all markets are considered canceled unless the match continues in the same NFL weekly schedule (Thursday - Wednesday local stadium time).'
        ]
      ];

      foreach ($rules as $rule) {
        Rule::create($rule);
      }
    }
}
