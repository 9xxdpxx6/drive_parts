<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Category;
use App\Models\Group;
use App\Models\Label;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' =>'admin','email'=>'qwe@qwe.qwe','phone_number'=>'123123', 'password'=>Hash::make('qwe'), 'role_id' =>'2']);

        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, 2802);
        $progressBar->start();

        Brand::factory(50)->create();
        $progressBar->advance(50);
        $this->printProgress('brands', 50);

        Category::factory(50)->create();
        $progressBar->advance(50);
        $this->printProgress('categories', 50);

        CarModel::factory(30)->create();
        $progressBar->advance(30);
        $this->printProgress('car_models', 30);

        Group::factory(10)->create();
        $progressBar->advance(10);
        $this->printProgress('groups', 10);

        User::factory(500)->create();
        $progressBar->advance(500);
        $this->printProgress('users', 500);

        Car::factory(500)->create();
        $progressBar->advance(500);
        $this->printProgress('cars', 500);

        $labels = Label::factory(6)->create();
        $progressBar->advance(6);
        $this->printProgress('labels', 6);

        OrderStatus::factory(6)->create();
        $progressBar->advance(6);
        $this->printProgress('order_statuses', 6);

        Provider::factory(50)->create();
        $progressBar->advance(50);
        $this->printProgress('providers', 50);

        Product::factory(1000)->create();
        $progressBar->advance(1000);
        $this->printProgress('products', 1000);

        $orders = Order::factory(200)->create();
        $progressBar->advance(200);
        $this->printProgress('orders', 200);

        foreach ($orders as $order) {
            $labelIds = $labels->random(random_int(1, 3))->pluck('id');
            $order->labels()->attach($labelIds);
        }

        Purchase::factory(400)->create();
        $progressBar->advance(400);
        $this->printProgress('purchases', 400);

        $progressBar->finish();
        $output->writeln(PHP_EOL);
    }

    /**
     * Print progress for a specific table.
     *
     * @param  string  $tableName
     * @param  int  $count
     * @return void
     */
    private function printProgress($tableName, $count)
    {
        $output = new ConsoleOutput();
        $output->writeln(' [' . $tableName . '] ' . $count . ' records added.');
    }
}

