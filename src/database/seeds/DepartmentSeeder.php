    <?php

use App\Department;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $it = Department::UpdateOrCreate(["name" => 'It']);
        $rd = Department::UpdateOrCreate(["name" => 'Rd']);
        $hr = Department::UpdateOrCreate(["name" => 'Hr']);
        $pm = Department::UpdateOrCreate(["name" => 'Pm']);
    }
}
