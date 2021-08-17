    <?php

use App\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee = Position::UpdateOrCreate(["name" => 'employee']);
        $supervisor = Position::UpdateOrCreate(["name" => 'supervisor']);
    }
}
