<?php
use Illuminate\Database\Seeder;
class UserTableSeeder extends Seeder {
	public function run(){
		DB::table('users')->insert
		(array
			(
				array('id'=>1,'name'=>'admin', 'password'=>Hash::make('1264123'),'email'=>'fangelith@gmail.com'),
			)
		);
	}
}