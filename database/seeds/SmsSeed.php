<?php


use Phinx\Seed\AbstractSeed;

class SmsSeed extends AbstractSeed
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
          'name' => $faker->text(25),
          'text'       => $faker->text,
          'phone' => $faker->phoneNumber,
          'created_at'       => date('Y-m-d H:i:s'),
          'status' => 'DELIVERED'
        ];
      }

      $this->table('sms_sendings')->insert($data)->save();
    }
}
