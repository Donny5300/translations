<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Support\Facades\Schema;

	class CreateTranslationsTable extends Migration
	{
		protected $tables = [ 'languages', 'groups', 'names', 'values' ];

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create( 'languages', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'title' );
				$table->string( 'title_short_two' );
				$table->string( 'title_short_three' );
				$table->timestamps();
				$table->softDeletes();
			} );

			Schema::create( 'groups', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'title' );
				$table->text( 'description' );
				$table->integer( 'group_id' )
					->nullable()
					->comment( 'Refer to self for multidimensional groups' );
				$table->timestamps();
				$table->softDeletes();

			} );

			Schema::create( 'names', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'title' );
				$table->integer( 'group_id' );
				$table->text( 'description' );
				$table->timestamps();
				$table->softDeletes();
			} );

			Schema::create( 'values', function ( Blueprint $table )
			{
				$table->increments( 'id' );
				$table->string( 'title' );
				$table->text( 'description' );
				$table->integer( 'language_id' );
				$table->integer( 'name_id' );
				$table->timestamps();
				$table->softDeletes();
			} );

		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
			foreach( $this->tables as $table )
			{
				Schema::dropIfExists( $table );
			}
		}
	}
