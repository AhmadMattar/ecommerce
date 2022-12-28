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
        $adminRole = Role::create([ 'name'  => 'admin', 'display_name' => 'Administration', 'description' => 'Admininstrator', 'allowed_route' => 'admin' ]);

        $superVisorRole = Role::create([ 'name'  => 'SuperVisor', 'display_name' => 'SuperVisor', 'description' => 'SuperVisor', 'allowed_route' => 'admin' ]);

        $customerRole = Role::create([ 'name'  => 'customer', 'display_name' => 'Customer', 'description' => 'Customer', 'allowed_route' => NULL ]);

        // Create Users
        $admin = User::create([ 'first_name'  => 'Admin', 'last_name' =>  'System', 'username' =>  'admin', 'email' => 'admin@ecommerce.test', 'email_verified_at' => now(), 'mobile' => '972598906699', 'user_image' => '', 'status' => 1, 'password' => bcrypt('123456789'), 'remember_token' => Str::random(10), ]);
        $admin->attachRole($adminRole);

        $superVisor = User::create([ 'first_name'  => 'SuperVisor', 'last_name' =>  'System', 'username' =>  'superVisor', 'email' => 'superVisor@ecommerce.test', 'email_verified_at' => now(), 'mobile' => '972598916699', 'user_image' => '', 'status' => 1, 'password' => bcrypt('123456789'), 'remember_token' => Str::random(10), ]);
        $superVisor->attachRole($superVisorRole);

        $customer = User::create([ 'first_name'  => 'Ahmed', 'last_name' =>  'Mattar', 'username' =>  'ahmed', 'email' => 'ahmed@gmail.com', 'email_verified_at' => now(), 'mobile' => '972598926699', 'user_image' => '', 'status' => 1, 'password' => bcrypt('123456789'), 'remember_token' => Str::random(10), ]);
        $customer->attachRole($customerRole);

        /*
         * Create 1000 fake users with their addresses.
         */

        User::factory()->count(1000)->hasAddresses(1)->create();


        //create permissions
        $manageMain = Permission::create([ 'name' => 'main', 'display_name' => 'Main', 'route' => 'index', 'module' => 'index', 'as' => 'index', 'icon' => 'fas fa-home', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '1', ]);
        $manageMain->parent_show = $manageMain->id;
        $manageMain->save();

        //create products categories permissions
        $manageProductCategories = Permission::create([ 'name' => 'manage_product_categories', 'display_name' => 'Categories', 'route' => 'product_categories', 'module' => 'product_categories', 'as' => 'product_categories.index', 'icon' => 'fas fa-file-archive', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '5', ]);
        $manageProductCategories->parent_show = $manageProductCategories->id;
        $manageProductCategories->save();
        //show all product categories to user if he has a role to login to product_categories.index
        $showProductCategories = Permission::create([ 'name' => 'show_product_categories', 'display_name' => 'Categories', 'route' => 'product_categories', 'module' => 'product_categories', 'as' => 'product_categories.index', 'icon' => 'fas fa-file-archive', 'parent' => $manageProductCategories->id, 'parent_original' => $manageProductCategories->id, 'parent_show' => $manageProductCategories->id, 'sidebar_link' => '1', 'appear' => '1', ]);
        $createProductCategories = Permission::create([ 'name' => 'create_product_categories', 'display_name' => 'Create Category', 'route' => 'product_categories', 'module' => 'product_categories', 'as' => 'product_categories.create', 'icon' => 'fas fa-plus-square', 'parent' => $manageProductCategories->id, 'parent_original' => $manageProductCategories->id, 'parent_show' => $manageProductCategories->id, 'sidebar_link' => '1', 'appear' => '0', ]);
        $displayProductCategories = Permission::create([ 'name' => 'display_product_categories', 'display_name' => 'Show Category', 'route' => 'product_categories', 'module' => 'product_categories', 'as' => 'product_categories.show', 'icon' => 'fas fa-eye-slash', 'parent' => $manageProductCategories->id, 'parent_original' => $manageProductCategories->id, 'parent_show' => $manageProductCategories->id, 'sidebar_link' => '1', 'appear' => '0', ]);
        $updateProductCategories = Permission::create([ 'name' => 'update_product_categories', 'display_name' => 'Update Category', 'route' => 'product_categories', 'module' => 'product_categories', 'as' => 'product_categories.edit', 'icon' => 'fas fa-pen-square', 'parent' => $manageProductCategories->id, 'parent_original' => $manageProductCategories->id, 'parent_show' => $manageProductCategories->id, 'sidebar_link' => '1', 'appear' => '0', ]);
        $delteProductCategories = Permission::create([ 'name' => 'delete_product_categories', 'display_name' => 'Delete Category', 'route' => 'product_categories', 'module' => 'product_categories', 'as' => 'product_categories.destroy', 'icon' => 'fas fa-trash-alt', 'parent' => $manageProductCategories->id, 'parent_original' => $manageProductCategories->id, 'parent_show' => $manageProductCategories->id, 'sidebar_link' => '1', 'appear' => '0', ]);


        //products tags
        $manageTags = Permission::create([ 'name' => 'manage_tags', 'display_name' => 'Tags', 'route' => 'tags', 'module' => 'tags', 'as' => 'tags.index', 'icon' => 'fas fa-tags', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '10', ]);
        $manageTags->parent_show = $manageTags->id;
        $manageTags->save();
        $showTags = Permission::create([ 'name' => 'show_tags', 'display_name' => 'tags', 'route' => 'tags', 'module' => 'tags', 'as' => 'tags.index', 'icon' => 'fas fa-tags', 'parent' => $manageTags->id, 'parent_original' => $manageTags->id, 'parent_show' => $manageTags->id, 'sidebar_link' => '1', 'appear' => '1', ]);
        $createTags = Permission::create([ 'name' => 'create_tags', 'display_name' => 'Create tags', 'route' => 'tags', 'module' => 'tags', 'as' => 'tags.create', 'icon' => 'fas fa-plus-square', 'parent' => $manageTags->id, 'parent_original' => $manageTags->id, 'parent_show' => $manageTags->id, 'sidebar_link' => '1', 'appear' => '0', ]);
        $displayTags = Permission::create([ 'name' => 'display_tags', 'display_name' => 'Show tags', 'route' => 'tags', 'module' => 'tags', 'as' => 'tags.show', 'icon' => 'fas fa-eye-slash', 'parent' => $manageTags->id, 'parent_original' => $manageTags->id, 'parent_show' => $manageTags->id, 'sidebar_link' => '1', 'appear' => '0', ]);
        $updateTags = Permission::create([ 'name' => 'update_tags', 'display_name' => 'Update tags', 'route' => 'tags', 'module' => 'tags', 'as' => 'tags.edit', 'icon' => 'fas fa-pen-square', 'parent' => $manageTags->id, 'parent_original' => $manageTags->id, 'parent_show' => $manageTags->id, 'sidebar_link' => '1', 'appear' => '0', ]);
        $delteTags = Permission::create([ 'name' => 'delete_tags', 'display_name' => 'Delete tags', 'route' => 'tags', 'module' => 'tags', 'as' => 'tags.destroy', 'icon' => 'fas fa-trash-alt', 'parent' => $manageTags->id, 'parent_original' => $manageTags->id, 'parent_show' => $manageTags->id, 'sidebar_link' => '1', 'appear' => '0', ]);

        //products
        $manageProducts = Permission::create([ 'name' => 'manage_products', 'display_name' => 'Products', 'route' => 'products', 'module' => 'products', 'as' => 'products.index', 'icon' => 'fas fa-file-archive', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '15', ]);
        $manageProducts->parent_show = $manageProducts->id;
        $manageProducts->save();
        $showProduct = Permission::create([ 'name' => 'show_products', 'display_name' => ' Products', 'route' => 'products', 'module' => 'products', 'as' => 'products.index', 'icon' => 'fas fa-file-archive', 'parent' => $manageProducts->id, 'parent_original' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'sidebar_link' => '1', 'appear' => '1', ]);
        $createProduct = Permission::create([ 'name' => 'create_products', 'display_name' => 'Create Products', 'route' => 'products', 'module' => 'products', 'as' => 'products.create', 'icon' => 'fas fa-plus-square', 'parent' => $manageProducts->id, 'parent_original' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'sidebar_link' => '1', 'appear' => '0', ]);
        $displayProduct = Permission::create([ 'name' => 'display_products', 'display_name' => 'Show Products', 'route' => 'products', 'module' => 'products', 'as' => 'products.show', 'icon' => 'fas fa-eye-slash', 'parent' => $manageProducts->id, 'parent_original' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'sidebar_link' => '1', 'appear' => '0', ]);
        $updateProduct = Permission::create([ 'name' => 'update_products', 'display_name' => 'Update Products', 'route' => 'products', 'module' => 'products', 'as' => 'products.edit', 'icon' => 'fas fa-pen-square', 'parent' => $manageProducts->id, 'parent_original' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $delteProduct = Permission::create([ 'name' => 'delete_products', 'display_name' => 'Delete Products', 'route' => 'products', 'module' => 'products', 'as' => 'products.destroy', 'icon' => 'fas fa-trash-alt', 'parent' => $manageProducts->id, 'parent_original' => $manageProducts->id, 'parent_show' => $manageProducts->id, 'sidebar_link' => '1', 'appear' => '0',]);

        //product coupons
        $manageProductCoupons = Permission::create(['name' => 'manage_product_coupons', 'display_name' => 'Coupons', 'route' => 'product_coupons', 'module' => 'product_coupons', 'as' => 'product_coupons.index', 'icon' => 'fas fa-percent', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '20',]);
        $manageProductCoupons->parent_show = $manageProductCoupons->id;
        $manageProductCoupons->save();
        $showProductCoupons = Permission::create(['name' => 'show_product_coupons', 'display_name' => 'Coupons', 'route' => 'product_coupons', 'module' => 'product_coupons', 'as' => 'product_coupons.index', 'icon' => 'fas fa-percent', 'parent' => $manageProductCoupons->id, 'parent_original' => $manageProductCoupons->id, 'parent_show' => $manageProductCoupons->id, 'sidebar_link' => '1', 'appear' => '1']);
        $createProductCoupons = Permission::create(['name' => 'create_product_coupons', 'display_name' => 'Create Coupon', 'route' => 'product_coupons', 'module' => 'product_coupons', 'as' => 'product_coupons.create', 'icon' => null, 'parent' => $manageProductCoupons->id, 'parent_original' => $manageProductCoupons->id, 'parent_show' => $manageProductCoupons->id, 'sidebar_link' => '1', 'appear' => '0']);
        $displayProductCoupons = Permission::create(['name' => 'display_product_coupons', 'display_name' => 'Show Coupon', 'route' => 'product_coupons', 'module' => 'product_coupons', 'as' => 'product_coupons.show', 'icon' => null, 'parent' => $manageProductCoupons->id, 'parent_original' => $manageProductCoupons->id, 'parent_show' => $manageProductCoupons->id, 'sidebar_link' => '1', 'appear' => '0']);
        $updateProductCoupons = Permission::create(['name' => 'update_product_coupons', 'display_name' => 'Update Coupon', 'route' => 'product_coupons', 'module' => 'product_coupons', 'as' => 'product_coupons.edit', 'icon' => null, 'parent' => $manageProductCoupons->id, 'parent_original' => $manageProductCoupons->id, 'parent_show' => $manageProductCoupons->id, 'sidebar_link' => '1', 'appear' => '0']);
        $deleteProductCoupons = Permission::create(['name' => 'delete_product_coupons', 'display_name' => 'Delete Coupon', 'route' => 'product_coupons', 'module' => 'product_coupons', 'as' => 'product_coupons.destroy', 'icon' => null, 'parent' => $manageProductCoupons->id, 'parent_original' => $manageProductCoupons->id, 'parent_show' => $manageProductCoupons->id, 'sidebar_link' => '1', 'appear' => '0']);

        //product reviews
        $manageProductReviews = Permission::create(['name' => 'manage_product_reviews', 'display_name' => 'Reviews', 'route' => 'product_reviews', 'module' => 'product_reviews', 'as' => 'product_reviews.index', 'icon' => 'fas fa-comment', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '25',]);
        $manageProductReviews->parent_show = $manageProductReviews->id; $manageProductReviews->save();

        $showProduct = Permission::create(['name' => 'show_product_reviews', 'display_name' => 'Reviews', 'route' => 'product_reviews', 'module' => 'product_reviews', 'as' => 'product_reviews.index', 'icon' => 'fas fa-comment', 'parent' => $manageProductReviews->id, 'parent_original' => $manageProductReviews->id, 'parent_show' => $manageProductReviews->id, 'sidebar_link' => '1', 'appear' => '1',]);
        $createProduct = Permission::create(['name' => 'create_product_reviews',    'display_name' => 'Create Review',  'route' => 'product_reviews', 'module' => 'product_reviews', 'as' => 'product_reviews.create',  'icon' => null, 'parent' => $manageProductReviews->id, 'parent_original' => $manageProductReviews->id, 'parent_show' => $manageProductReviews->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $displayProduct = Permission::create([ 'name' => 'display_product_reviews', 'display_name' => 'Show Review',    'route' => 'product_reviews', 'module' => 'product_reviews', 'as' => 'product_reviews.show',    'icon' => null,   'parent' => $manageProductReviews->id, 'parent_original' => $manageProductReviews->id, 'parent_show' => $manageProductReviews->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $updateProduct = Permission::create(['name' => 'update_product_reviews',    'display_name' => 'Update Review',  'route' => 'product_reviews', 'module' => 'product_reviews', 'as' => 'product_reviews.edit',    'icon' => null,  'parent' => $manageProductReviews->id,'parent_original' => $manageProductReviews->id,'parent_show' => $manageProductReviews->id,'sidebar_link' => '1','appear' => '0',]);
        $delteProduct = Permission::create(['name' => 'delete_product_reviews',     'display_name' => 'Delete Review',  'route' => 'product_reviews', 'module' => 'product_reviews', 'as' => 'product_reviews.destroy', 'icon' => null,   'parent' => $manageProductReviews->id,'parent_original' => $manageProductReviews->id,'parent_show' => $manageProductReviews->id,'sidebar_link' => '1','appear' => '0',]);

        //customers
        $manageCustomers = Permission::create(['name' => 'manage_customers', 'display_name' => 'Customers', 'route' => 'customers', 'module' => 'customers', 'as' => 'customers.index', 'icon' => 'fas fa-user', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '30',]);
        $manageCustomers->parent_show = $manageCustomers->id; $manageCustomers->save();

        $showCustomer = Permission::create(['name' => 'show_customers', 'display_name' => 'Customers', 'route' => 'customers', 'module' => 'customers', 'as' => 'customers.index', 'icon' => 'fas fa-user', 'parent' => $manageCustomers->id, 'parent_original' => $manageCustomers->id, 'parent_show' => $manageCustomers->id, 'sidebar_link' => '1', 'appear' => '1',]);
        $createCustomer = Permission::create(['name' => 'create_customers',    'display_name' => 'Create Customer',  'route' => 'customers', 'module' => 'customers', 'as' => 'customers.create',  'icon' => null, 'parent' => $manageCustomers->id, 'parent_original' => $manageCustomers->id, 'parent_show' => $manageCustomers->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $displayCustomer = Permission::create([ 'name' => 'display_customers', 'display_name' => 'Show Customer',    'route' => 'customers', 'module' => 'customers', 'as' => 'customers.show',    'icon' => null,   'parent' => $manageCustomers->id, 'parent_original' => $manageCustomers->id, 'parent_show' => $manageCustomers->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $updateCustomer = Permission::create(['name' => 'update_customers',    'display_name' => 'Update Customer',  'route' => 'customers', 'module' => 'customers', 'as' => 'customers.edit',    'icon' => null,  'parent' => $manageCustomers->id,'parent_original' => $manageCustomers->id,'parent_show' => $manageCustomers->id,'sidebar_link' => '1','appear' => '0',]);
        $delteCustomer = Permission::create(['name' => 'delete_customers',     'display_name' => 'Delete Customer',  'route' => 'customers', 'module' => 'customers', 'as' => 'customers.destroy', 'icon' => null,   'parent' => $manageCustomers->id,'parent_original' => $manageCustomers->id,'parent_show' => $manageCustomers->id,'sidebar_link' => '1','appear' => '0',]);

        //customer addresses
        $manageCustomerAddresses = Permission::create(['name' => 'manage_customer_addresses', 'display_name' => 'Customer Addresses', 'route' => 'customer_addresses', 'module' => 'customer_addresses', 'as' => 'customer_addresses.index', 'icon' => 'fas fa-map-marked-alt', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '35',]);
        $manageCustomerAddresses->parent_show = $manageCustomerAddresses->id; $manageCustomerAddresses->save();

        $showCustomerAddresses = Permission::create(['name' => 'show_customer_addresses', 'display_name' => 'Customer Addresses', 'route' => 'customer_addresses', 'module' => 'customer_addresses', 'as' => 'customer_addresses.index', 'icon' => 'fas fa-map-marked-alt', 'parent' => $manageCustomerAddresses->id, 'parent_original' => $manageCustomerAddresses->id, 'parent_show' => $manageCustomerAddresses->id, 'sidebar_link' => '1', 'appear' => '1',]);
        $createCustomerAddresses = Permission::create(['name' => 'create_customer_addresses',    'display_name' => 'Create Addresses',  'route' => 'customer_addresses', 'module' => 'customer_addresses', 'as' => 'customer_addresses.create',  'icon' => null, 'parent' => $manageCustomerAddresses->id, 'parent_original' => $manageCustomerAddresses->id, 'parent_show' => $manageCustomerAddresses->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $displayCustomerAddresses = Permission::create([ 'name' => 'display_customer_addresses', 'display_name' => 'Show Addresses',    'route' => 'customer_addresses', 'module' => 'customer_addresses', 'as' => 'customer_addresses.show',    'icon' => null,   'parent' => $manageCustomerAddresses->id, 'parent_original' => $manageCustomerAddresses->id, 'parent_show' => $manageCustomerAddresses->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $updateCustomerAddresses = Permission::create(['name' => 'update_customer_addresses',    'display_name' => 'Update Addresses',  'route' => 'customer_addresses', 'module' => 'customer_addresses', 'as' => 'customer_addresses.edit',    'icon' => null,  'parent' => $manageCustomerAddresses->id,'parent_original' => $manageCustomerAddresses->id,'parent_show' => $manageCustomerAddresses->id,'sidebar_link' => '1','appear' => '0',]);
        $delteCustomerAddresses = Permission::create(['name' => 'delete_customer_addresses',     'display_name' => 'Delete Addresses',  'route' => 'customer_addresses', 'module' => 'customer_addresses', 'as' => 'customer_addresses.destroy', 'icon' => null,   'parent' => $manageCustomerAddresses->id,'parent_original' => $manageCustomerAddresses->id,'parent_show' => $manageCustomerAddresses->id,'sidebar_link' => '1','appear' => '0',]);

        //Orders
        $manageOrders = Permission::create(['name' => 'manage_orders', 'display_name' => 'Orders', 'route' => 'orders', 'module' => 'orders', 'as' => 'orders.index', 'icon' => 'fas fa-shopping-basket', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '40',]);
        $manageOrders->parent_show = $manageOrders->id; $manageOrders->save();

        $showOrders = Permission::create(['name' => 'show_orders', 'display_name' => 'Orders', 'route' => 'orders', 'module' => 'orders', 'as' => 'orders.index', 'icon' => 'fas fa-map-marked-alt', 'parent' => $manageOrders->id, 'parent_original' => $manageOrders->id, 'parent_show' => $manageOrders->id, 'sidebar_link' => '1', 'appear' => '1',]);
        $createOrders = Permission::create(['name' => 'create_orders',    'display_name' => 'Create Orders',  'route' => 'orders', 'module' => 'orders', 'as' => 'orders.create',  'icon' => null, 'parent' => $manageOrders->id, 'parent_original' => $manageOrders->id, 'parent_show' => $manageOrders->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $displayOrders = Permission::create([ 'name' => 'display_orders', 'display_name' => 'Show Orders',    'route' => 'orders', 'module' => 'orders', 'as' => 'orders.show',    'icon' => null,   'parent' => $manageOrders->id, 'parent_original' => $manageOrders->id, 'parent_show' => $manageOrders->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $updateOrders = Permission::create(['name' => 'update_orders',    'display_name' => 'Update Orders',  'route' => 'orders', 'module' => 'orders', 'as' => 'orders.edit',    'icon' => null,  'parent' => $manageOrders->id,'parent_original' => $manageOrders->id,'parent_show' => $manageOrders->id,'sidebar_link' => '1','appear' => '0',]);
        $delteOrders = Permission::create(['name' => 'delete_orders',     'display_name' => 'Delete Orders',  'route' => 'orders', 'module' => 'orders', 'as' => 'orders.destroy', 'icon' => null,   'parent' => $manageOrders->id,'parent_original' => $manageOrders->id,'parent_show' => $manageOrders->id,'sidebar_link' => '1','appear' => '0',]);

        //supervisors
        $manageSupervisors = Permission::create(['name' => 'manage_supervisors', 'display_name' => 'Supervisors', 'route' => 'customers', 'module' => 'customers', 'as' => 'customers.index', 'icon' => 'fas fa-user', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '0', 'appear' => '1', 'ordering' => '45',]);
        $manageSupervisors->parent_show = $manageSupervisors->id; $manageSupervisors->save();

        $showSupervisor = Permission::create(['name' => 'show_supervisors', 'display_name' => 'Supervisors', 'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.index', 'icon' => 'fas fa-user', 'parent' => $manageSupervisors->id, 'parent_original' => $manageSupervisors->id, 'parent_show' => $manageSupervisors->id, 'sidebar_link' => '1', 'appear' => '1',]);
        $createSupervisor = Permission::create(['name' => 'create_supervisors',    'display_name' => 'Create Supervisor',  'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.create',  'icon' => null, 'parent' => $manageSupervisors->id, 'parent_original' => $manageSupervisors->id, 'parent_show' => $manageSupervisors->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $displaySupervisor = Permission::create([ 'name' => 'display_supervisors', 'display_name' => 'Show Supervisor',    'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.show',    'icon' => null,   'parent' => $manageSupervisors->id, 'parent_original' => $manageSupervisors->id, 'parent_show' => $manageSupervisors->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $updateSupervisor = Permission::create(['name' => 'update_supervisors',    'display_name' => 'Update Supervisor',  'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.edit',    'icon' => null,  'parent' => $manageSupervisors->id,'parent_original' => $manageSupervisors->id,'parent_show' => $manageSupervisors->id,'sidebar_link' => '1','appear' => '0',]);
        $delteSupervisor = Permission::create(['name' => 'delete_supervisors',     'display_name' => 'Delete Supervisor',  'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.destroy', 'icon' => null,   'parent' => $manageSupervisors->id,'parent_original' => $manageSupervisors->id,'parent_show' => $manageSupervisors->id,'sidebar_link' => '1','appear' => '0',]);

        //countries
        $manageCountries = Permission::create(['name' => 'manage_countries', 'display_name' => 'Countries', 'route' => 'countries', 'module' => 'countries', 'as' => 'countries.index', 'icon' => 'fas fa-globe', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '50',]);
        $manageCountries->parent_show = $manageCountries->id; $manageCountries->save();

        $showCountry = Permission::create(['name' => 'show_countries', 'display_name' => 'Countries', 'route' => 'countries', 'module' => 'countries', 'as' => 'countries.index', 'icon' => 'fas fa-globe', 'parent' => $manageCountries->id, 'parent_original' => $manageCountries->id, 'parent_show' => $manageCountries->id, 'sidebar_link' => '1', 'appear' => '1',]);
        $createCountry = Permission::create(['name' => 'create_countries',    'display_name' => 'Create Country',  'route' => 'countries', 'module' => 'countries', 'as' => 'countries.create',  'icon' => null, 'parent' => $manageCountries->id, 'parent_original' => $manageCountries->id, 'parent_show' => $manageCountries->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $displayCountry = Permission::create([ 'name' => 'display_countries', 'display_name' => 'Show Country',    'route' => 'countries', 'module' => 'countries', 'as' => 'countries.show',    'icon' => null,   'parent' => $manageCountries->id, 'parent_original' => $manageCountries->id, 'parent_show' => $manageCountries->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $updateCountry = Permission::create(['name' => 'update_countries',    'display_name' => 'Update Country',  'route' => 'countries', 'module' => 'countries', 'as' => 'countries.edit',    'icon' => null,  'parent' => $manageCountries->id,'parent_original' => $manageCountries->id,'parent_show' => $manageCountries->id,'sidebar_link' => '1','appear' => '0',]);
        $delteCountry = Permission::create(['name' => 'delete_countries',     'display_name' => 'Delete Country',  'route' => 'countries', 'module' => 'countries', 'as' => 'countries.destroy', 'icon' => null,   'parent' => $manageCountries->id,'parent_original' => $manageCountries->id,'parent_show' => $manageCountries->id,'sidebar_link' => '1','appear' => '0',]);

        //states
        $manageStates = Permission::create(['name' => 'manage_states', 'display_name' => 'States', 'route' => 'states', 'module' => 'states', 'as' => 'states.index', 'icon' => 'fas fa-map-marker-alt', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '55',]);
        $manageStates->parent_show = $manageStates->id; $manageStates->save();

        $showState = Permission::create(['name' => 'show_states', 'display_name' => 'States', 'route' => 'states', 'module' => 'states', 'as' => 'states.index', 'icon' => 'fas fa-map-marker-alt', 'parent' => $manageStates->id, 'parent_original' => $manageStates->id, 'parent_show' => $manageStates->id, 'sidebar_link' => '1', 'appear' => '1',]);
        $createState = Permission::create(['name' => 'create_states',    'display_name' => 'Create State',  'route' => 'states', 'module' => 'states', 'as' => 'states.create',  'icon' => null, 'parent' => $manageStates->id, 'parent_original' => $manageStates->id, 'parent_show' => $manageStates->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $displayState = Permission::create([ 'name' => 'display_states', 'display_name' => 'Show State',    'route' => 'states', 'module' => 'states', 'as' => 'states.show',    'icon' => null,   'parent' => $manageStates->id, 'parent_original' => $manageStates->id, 'parent_show' => $manageStates->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $updateState = Permission::create(['name' => 'update_states',    'display_name' => 'Update State',  'route' => 'states', 'module' => 'states', 'as' => 'states.edit',    'icon' => null,  'parent' => $manageStates->id,'parent_original' => $manageStates->id,'parent_show' => $manageStates->id,'sidebar_link' => '1','appear' => '0',]);
        $delteState = Permission::create(['name' => 'delete_states',     'display_name' => 'Delete State',  'route' => 'states', 'module' => 'states', 'as' => 'states.destroy', 'icon' => null,   'parent' => $manageStates->id,'parent_original' => $manageStates->id,'parent_show' => $manageStates->id,'sidebar_link' => '1','appear' => '0',]);


        //cities
        $manageCities = Permission::create(['name' => 'manage_cities', 'display_name' => 'Cities', 'route' => 'cities', 'module' => 'cities', 'as' => 'cities.index', 'icon' => 'fas fa-city', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '60',]);
        $manageCities->parent_show = $manageCities->id; $manageCities->save();

        $showCity = Permission::create(['name' => 'show_cities', 'display_name' => 'Cities', 'route' => 'cities', 'module' => 'cities', 'as' => 'cities.index', 'icon' => 'fas fa-city', 'parent' => $manageCities->id, 'parent_original' => $manageCities->id, 'parent_show' => $manageCities->id, 'sidebar_link' => '1', 'appear' => '1',]);
        $createCity = Permission::create(['name' => 'create_cities',    'display_name' => 'Create City',  'route' => 'cities', 'module' => 'cities', 'as' => 'cities.create',  'icon' => null, 'parent' => $manageCities->id, 'parent_original' => $manageCities->id, 'parent_show' => $manageCities->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $displayCity = Permission::create([ 'name' => 'display_cities', 'display_name' => 'Show City',    'route' => 'cities', 'module' => 'cities', 'as' => 'cities.show',    'icon' => null,   'parent' => $manageCities->id, 'parent_original' => $manageCities->id, 'parent_show' => $manageCities->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $updateCity = Permission::create(['name' => 'update_cities',    'display_name' => 'Update City',  'route' => 'cities', 'module' => 'cities', 'as' => 'cities.edit',    'icon' => null,  'parent' => $manageCities->id,'parent_original' => $manageCities->id,'parent_show' => $manageCities->id,'sidebar_link' => '1','appear' => '0',]);
        $delteCity = Permission::create(['name' => 'delete_cities',     'display_name' => 'Delete City',  'route' => 'cities', 'module' => 'cities', 'as' => 'cities.destroy', 'icon' => null,   'parent' => $manageCities->id,'parent_original' => $manageCities->id,'parent_show' => $manageCities->id,'sidebar_link' => '1','appear' => '0',]);

        //shipping companies
        $manageShippingCompanies = Permission::create(['name' => 'manage_shipping_companies', 'display_name' => 'ShippingCompanies', 'route' => 'shipping_companies', 'module' => 'shipping_companies', 'as' => 'shipping_companies.index', 'icon' => 'fas fa-truck', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '90',]);
        $manageShippingCompanies->parent_show = $manageShippingCompanies->id; $manageShippingCompanies->save();

        $showShippingCompany = Permission::create(['name' => 'show_shipping_companies', 'display_name' => 'ShippingCompanies', 'route' => 'shipping_companies', 'module' => 'shipping_companies', 'as' => 'shipping_companies.index', 'icon' => 'fas fa-truck', 'parent' => $manageShippingCompanies->id, 'parent_original' => $manageShippingCompanies->id, 'parent_show' => $manageShippingCompanies->id, 'sidebar_link' => '1', 'appear' => '1',]);
        $createShippingCompany = Permission::create(['name' => 'create_shipping_companies',    'display_name' => 'Create Shipping Company',  'route' => 'shipping_companies', 'module' => 'shipping_companies', 'as' => 'shipping_companies.create',  'icon' => null, 'parent' => $manageShippingCompanies->id, 'parent_original' => $manageShippingCompanies->id, 'parent_show' => $manageShippingCompanies->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $displayShippingCompany = Permission::create([ 'name' => 'display_shipping_companies', 'display_name' => 'Show Shipping Company',    'route' => 'shipping_companies', 'module' => 'shipping_companies', 'as' => 'shipping_companies.show',    'icon' => null,   'parent' => $manageShippingCompanies->id, 'parent_original' => $manageShippingCompanies->id, 'parent_show' => $manageShippingCompanies->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $updateShippingCompany = Permission::create(['name' => 'update_shipping_companies',    'display_name' => 'Update Shipping Company',  'route' => 'shipping_companies', 'module' => 'shipping_companies', 'as' => 'shipping_companies.edit',    'icon' => null,  'parent' => $manageShippingCompanies->id,'parent_original' => $manageShippingCompanies->id,'parent_show' => $manageShippingCompanies->id,'sidebar_link' => '1','appear' => '0',]);
        $delteShippingCompany = Permission::create(['name' => 'delete_shipping_companies',     'display_name' => 'Delete Shipping Company',  'route' => 'shipping_companies', 'module' => 'shipping_companies', 'as' => 'shipping_companies.destroy', 'icon' => null,   'parent' => $manageShippingCompanies->id,'parent_original' => $manageShippingCompanies->id,'parent_show' => $manageShippingCompanies->id,'sidebar_link' => '1','appear' => '0',]);

        //payment methods
        $managePaymentMethods = Permission::create(['name' => 'manage_payment_methods', 'display_name' => 'PaymentMethods', 'route' => 'payment_methods', 'module' => 'payment_methods', 'as' => 'payment_methods.index', 'icon' => 'fas fa-dollar-sign', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '100',]);
        $managePaymentMethods->parent_show = $managePaymentMethods->id; $managePaymentMethods->save();

        $showPaymentMethod = Permission::create(['name' => 'show_payment_methods', 'display_name' => 'PaymentMethods', 'route' => 'payment_methods', 'module' => 'payment_methods', 'as' => 'payment_methods.index', 'icon' => 'fas fa-dollar-sign', 'parent' => $managePaymentMethods->id, 'parent_original' => $managePaymentMethods->id, 'parent_show' => $managePaymentMethods->id, 'sidebar_link' => '1', 'appear' => '1',]);
        $createPaymentMethod = Permission::create(['name' => 'create_payment_methods',    'display_name' => 'Create Payment Method',  'route' => 'payment_methods', 'module' => 'payment_methods', 'as' => 'payment_methods.create',  'icon' => null, 'parent' => $managePaymentMethods->id, 'parent_original' => $managePaymentMethods->id, 'parent_show' => $managePaymentMethods->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $displayPaymentMethod = Permission::create([ 'name' => 'display_payment_methods', 'display_name' => 'Show Payment Method',    'route' => 'payment_methods', 'module' => 'payment_methods', 'as' => 'payment_methods.show',    'icon' => null,   'parent' => $managePaymentMethods->id, 'parent_original' => $managePaymentMethods->id, 'parent_show' => $managePaymentMethods->id, 'sidebar_link' => '1', 'appear' => '0',]);
        $updatePaymentMethod = Permission::create(['name' => 'update_payment_methods',    'display_name' => 'Update Payment Method',  'route' => 'payment_methods', 'module' => 'payment_methods', 'as' => 'payment_methods.edit',    'icon' => null,  'parent' => $managePaymentMethods->id,'parent_original' => $managePaymentMethods->id,'parent_show' => $managePaymentMethods->id,'sidebar_link' => '1','appear' => '0',]);
        $deltePaymentMethod = Permission::create(['name' => 'delete_payment_methods',     'display_name' => 'Delete Payment Method',  'route' => 'payment_methods', 'module' => 'payment_methods', 'as' => 'payment_methods.destroy', 'icon' => null,   'parent' => $managePaymentMethods->id,'parent_original' => $managePaymentMethods->id,'parent_show' => $managePaymentMethods->id,'sidebar_link' => '1','appear' => '0',]);

    }
}
