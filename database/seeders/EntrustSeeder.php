<?php

namespace Database\Seeders;

use App\Models\Permission;
use Faker\Factory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class EntrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        // Create Roles (admin, supervisor, customer)
        $adminRole = Role::create([
            'name'  => 'admin',
            'display_name' => 'Administration',
            'description' => 'Admininstrator',
            'allowed_route' => 'admin'
        ]);

        $superVisorRole = Role::create([
            'name'  => 'SuperVisor',
            'display_name' => 'SuperVisor',
            'description' => 'SuperVisor',
            'allowed_route' => 'admin'
        ]);

        $customerRole = Role::create([
            'name'  => 'customer',
            'display_name' => 'Customer',
            'description' => 'Customer',
            'allowed_route' => NULL
        ]);

        // Create Users
        $admin = User::create([
            'first_name'  => 'Admin',
            'last_name' =>  'System',
            'username' =>  'admin',
            'email' => 'admin@ecommerce.test',
            'email_verified_at' => now(),
            'mobile' => '972598906699',
            'user_image' => 'avatar.svg',
            'status' => 1,
            'password' => bcrypt('123456789'),
            'remember_token' => Str::random(10),
        ]);
        $admin->attachRole($adminRole);

        $superVisor = User::create([
            'first_name'  => 'SuperVisor',
            'last_name' =>  'System',
            'username' =>  'superVisor',
            'email' => 'superVisor@ecommerce.test',
            'email_verified_at' => now(),
            'mobile' => '972598916699',
            'user_image' => 'avatar.svg',
            'status' => 1,
            'password' => bcrypt('123456789'),
            'remember_token' => Str::random(10),
        ]);
        $superVisor->attachRole($superVisorRole);

        $customer = User::create([
            'first_name'  => 'Ahmed',
            'last_name' =>  'Mattar',
            'username' =>  'ahmed',
            'email' => 'ahmed@gmail.com',
            'email_verified_at' => now(),
            'mobile' => '972598926699',
            'user_image' => 'avatar.svg',
            'status' => 1,
            'password' => bcrypt('123456789'),
            'remember_token' => Str::random(10),
        ]);
        $customer->attachRole($customerRole);

        for ($i = 0; $i < 21; $i++) {
            $random_customer = User::create([
                'first_name'  => $faker->firstName,
                'last_name' =>  $faker->lastName,
                'username' =>  $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'mobile' => '97259892' . $faker->unique()->numberBetween(1000, 9999),
                'user_image' => 'avatar.svg',
                'status' => 1,
                'password' => bcrypt('123456789'),
                'remember_token' => Str::random(10),
            ]);
            $random_customer->attachRole($customerRole);
        }

        //create permissions
        $manageMain = Permission::create([
            'name' => 'main',
            'display_name' => 'Main',
            'route' => 'index',
            'module' => 'index',
            'as' => 'index',
            'icon' => 'fas fa-home',
            'parent' => '0',
            'parent_original' => '0',
            'sidebar_link' => '1',
            'appear' => '1',
            'ordering' => '1',
        ]);
        $manageMain->parent_show = $manageMain->id;
        $manageMain->save();

        //create products categories permissions
        $manageProductCategories = Permission::create([
            'name' => 'manage_product_categories',
            'display_name' => 'Categories',
            'route' => 'product_categories',
            'module' => 'product_categories',
            'as' => 'product_categories.index',
            'icon' => 'fas fa-file-archive',
            'parent' => '0',
            'parent_original' => '0',
            'sidebar_link' => '1',
            'appear' => '1',
            'ordering' => '5',
        ]);
        $manageProductCategories->parent_show = $manageProductCategories->id;
        $manageProductCategories->save();

        //show all product categories to user if he has a role to login to product_categories.index
        $showProductCategories = Permission::create([
            'name' => 'show_product_categories',
            'display_name' => 'Categories',
            'route' => 'product_categories',
            'module' => 'product_categories',
            'as' => 'product_categories.index',
            'icon' => 'fas fa-file-archive',
            'parent' => $manageProductCategories->id,
            'parent_original' => $manageProductCategories->id,
            'parent_show' => $manageProductCategories->id,
            'sidebar_link' => '1',
            'appear' => '1',
        ]);

        $createProductCategories = Permission::create([
            'name' => 'create_product_categories',
            'display_name' => 'Create Category',
            'route' => 'product_categories',
            'module' => 'product_categories',
            'as' => 'product_categories.create',
            'icon' => 'fas fa-plus-square',
            'parent' => $manageProductCategories->id,
            'parent_original' => $manageProductCategories->id,
            'parent_show' => $manageProductCategories->id,
            'sidebar_link' => '1',
            'appear' => '0', //not appear in sidebar
        ]);
        $displayProductCategories = Permission::create([
            'name' => 'display_product_categories',
            'display_name' => 'Show Category',
            'route' => 'product_categories',
            'module' => 'product_categories',
            'as' => 'product_categories.show',
            'icon' => 'fas fa-eye-slash',
            'parent' => $manageProductCategories->id,
            'parent_original' => $manageProductCategories->id,
            'parent_show' => $manageProductCategories->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);

        $updateProductCategories = Permission::create([
            'name' => 'update_product_categories',
            'display_name' => 'Update Category',
            'route' => 'product_categories',
            'module' => 'product_categories',
            'as' => 'product_categories.edit',
            'icon' => 'fas fa-pen-square',
            'parent' => $manageProductCategories->id,
            'parent_original' => $manageProductCategories->id,
            'parent_show' => $manageProductCategories->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);

        $delteProductCategories = Permission::create([
            'name' => 'delete_product_categories',
            'display_name' => 'Delete Category',
            'route' => 'product_categories',
            'module' => 'product_categories',
            'as' => 'product_categories.destroy',
            'icon' => 'fas fa-trash-alt',
            'parent' => $manageProductCategories->id,
            'parent_original' => $manageProductCategories->id,
            'parent_show' => $manageProductCategories->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);


        //products tags
        $manageTags = Permission::create([
            'name' => 'manage_tags',
            'display_name' => 'Tags',
            'route' => 'tags',
            'module' => 'tags',
            'as' => 'tags.index',
            'icon' => 'fas fa-tags',
            'parent' => '0',
            'parent_original' => '0',
            'sidebar_link' => '1',
            'appear' => '1',
            'ordering' => '10',
        ]);
        $manageTags->parent_show = $manageTags->id;
        $manageTags->save();

        $showTags = Permission::create([
            'name' => 'show_tags',
            'display_name' => 'tags',
            'route' => 'tags',
            'module' => 'tags',
            'as' => 'tags.index',
            'icon' => 'fas fa-tags',
            'parent' => $manageTags->id,
            'parent_original' => $manageTags->id,
            'parent_show' => $manageTags->id,
            'sidebar_link' => '1',
            'appear' => '1',
        ]);

        $createTags = Permission::create([
            'name' => 'create_tags',
            'display_name' => 'Create tags',
            'route' => 'tags',
            'module' => 'tags',
            'as' => 'tags.create',
            'icon' => 'fas fa-plus-square',
            'parent' => $manageTags->id,
            'parent_original' => $manageTags->id,
            'parent_show' => $manageTags->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);
        $displayTags = Permission::create([
            'name' => 'display_tags',
            'display_name' => 'Show tags',
            'route' => 'tags',
            'module' => 'tags',
            'as' => 'tags.show',
            'icon' => 'fas fa-eye-slash',
            'parent' => $manageTags->id,
            'parent_original' => $manageTags->id,
            'parent_show' => $manageTags->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);

        $updateTags = Permission::create([
            'name' => 'update_tags',
            'display_name' => 'Update tags',
            'route' => 'tags',
            'module' => 'tags',
            'as' => 'tags.edit',
            'icon' => 'fas fa-pen-square',
            'parent' => $manageTags->id,
            'parent_original' => $manageTags->id,
            'parent_show' => $manageTags->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);

        $delteTags = Permission::create([
            'name' => 'delete_tags',
            'display_name' => 'Delete tags',
            'route' => 'tags',
            'module' => 'tags',
            'as' => 'tags.destroy',
            'icon' => 'fas fa-trash-alt',
            'parent' => $manageTags->id,
            'parent_original' => $manageTags->id,
            'parent_show' => $manageTags->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);

        //products
        $manageProducts = Permission::create([
            'name' => 'manage_products',
            'display_name' => 'Products',
            'route' => 'products',
            'module' => 'products',
            'as' => 'products.index',
            'icon' => 'fas fa-file-archive',
            'parent' => '0',
            'parent_original' => '0',
            'sidebar_link' => '1',
            'appear' => '1',
            'ordering' => '10',
        ]);
        $manageProducts->parent_show = $manageProducts->id;
        $manageProducts->save();

        $showProduct = Permission::create([
            'name' => 'show_products',
            'display_name' => ' Products',
            'route' => 'products',
            'module' => 'products',
            'as' => 'products.index',
            'icon' => 'fas fa-file-archive',
            'parent' => $manageProducts->id,
            'parent_original' => $manageProducts->id,
            'parent_show' => $manageProducts->id,
            'sidebar_link' => '1',
            'appear' => '1',
        ]);

        $createProduct = Permission::create([
            'name' => 'create_products',
            'display_name' => 'Create Products',
            'route' => 'products',
            'module' => 'products',
            'as' => 'products.create',
            'icon' => 'fas fa-plus-square',
            'parent' => $manageProducts->id,
            'parent_original' => $manageProducts->id,
            'parent_show' => $manageProducts->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);
        $displayProduct = Permission::create([
            'name' => 'display_products',
            'display_name' => 'Show Products',
            'route' => 'products',
            'module' => 'products',
            'as' => 'products.show',
            'icon' => 'fas fa-eye-slash',
            'parent' => $manageProducts->id,
            'parent_original' => $manageProducts->id,
            'parent_show' => $manageProducts->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);

        $updateProduct = Permission::create([
            'name' => 'update_products',
            'display_name' => 'Update Products',
            'route' => 'products',
            'module' => 'products',
            'as' => 'products.edit',
            'icon' => 'fas fa-pen-square',
            'parent' => $manageProducts->id,
            'parent_original' => $manageProducts->id,
            'parent_show' => $manageProducts->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);

        $delteProduct = Permission::create([
            'name' => 'delete_products',
            'display_name' => 'Delete Products',
            'route' => 'products',
            'module' => 'products',
            'as' => 'products.destroy',
            'icon' => 'fas fa-trash-alt',
            'parent' => $manageProducts->id,
            'parent_original' => $manageProducts->id,
            'parent_show' => $manageProducts->id,
            'sidebar_link' => '1',
            'appear' => '0',
        ]);
    }
}
