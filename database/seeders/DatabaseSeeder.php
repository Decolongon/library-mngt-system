<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $roleLibrarian = Role::create(['name' => 'librarian']);
        $bookBorrower = Role::create(['name' => 'book_borrower']);
         $superAdmin = Role::create(['name' => 'super_admin']);

       $user1 = User::create([
            'email' => 'librarian@gmail.com',
            'name' => 'Librarian',
            'password' => bcrypt('12345678')
        ]);

        $user1->assignRole($roleLibrarian);

         $user2 = User::create([
            'email' => 'book_borrower@gmail.com',
            'name' => 'book_borrower',
            'password' => bcrypt('12345678')
        ]);

        $user2->assignRole($bookBorrower);

       $user3 = User::create([
            'name' => 'Super admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $user3->assignRole($superAdmin);

    }
}
