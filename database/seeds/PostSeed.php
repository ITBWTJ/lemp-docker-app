<?php


use Phinx\Seed\AbstractSeed;

class PostSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 0; $i < 15; $i++) {
            $data[] = [
                'title' => $faker->title,
                'message'       => $faker->text,
                'user_id' => $faker->numberBetween(1, 5),
                'created_at'       => date('Y-m-d H:i:s'),
            ];
        }

        $this->table('posts')->insert($data)->save();
    }
}
