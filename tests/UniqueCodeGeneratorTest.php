<?php

namespace TheJawker\SuperRandom\Test;

use Illuminate\Support\Facades\DB;
use TheJawker\SuperRandom\SuperRandom;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UniqueCodeGeneratorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Schema::create('test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
        });
    }

    /** @test */
    public function generator_can_generate_a_code()
    {
        $invitationCode = SuperRandom::generate();

        $this->assertNotNull($invitationCode);
        $this->assertTrue(is_string($invitationCode));
    }

    /** @test */
    public function has_a_default_length()
    {
        $invitationCode = SuperRandom::generate();

        $this->assertEquals(8, strlen($invitationCode));
    }

    /** @test */
    public function complies_with_the_specified_length()
    {
        $invitationCode = SuperRandom::generate([
            'length' => '6'
        ]);

        $this->assertEquals(6, strlen($invitationCode));
    }

    /** @test */
    public function a_filled_db_makes_it_db_aware()
    {
        DB::table('test')->insert(['code' => 'aa']);

        SuperRandom::setConfig([
            'table' => 'test',
            'column' => 'code',
        ]);

        $this->assertTrue(SuperRandom::isDbAware());
    }

    /** @test */
    public function not_existing_table_and_column_resolves_to_not_database_aware()
    {
        DB::table('test')->insert(['code' => 'aa']);

        SuperRandom::setConfig([
            'table' => 'test',
            'column' => 'code',
        ]);

        $this->assertTrue(SuperRandom::isDbAware());

        SuperRandom::setConfig([
            'table' => 'test',
            'column' => 'doesnt-exist',
        ]);

        $this->assertFalse(SuperRandom::isDbAware());

        SuperRandom::setConfig([
            'table' => 'doesnt-exist',
            'column' => 'test',
        ]);

        $this->assertFalse(SuperRandom::isDbAware());
    }

    /** @test */
    public function checks_for_uniqueness_on_the_table()
    {
        DB::table('test')->insert(['code' => 'aa']);
        DB::table('test')->insert(['code' => 'ab']);
        DB::table('test')->insert(['code' => 'ba']);

        $code = SuperRandom::generate([
            'table' => 'test',
            'column' => 'code',
            'length' => 2,
            'chars' => 'ab'
        ]);

        $this->assertEquals('bb', $code);
    }

    /** @test */
    public function a_shortcut_for_table_column_is_present()
    {
        $generator = SuperRandom::for('table.column');

        $this->assertEquals('table', $generator->table);
        $this->assertEquals('column', $generator->column);
    }

    /** @test */
    public function a_model_can_be_passed_alternatively_column_will_be_code()
    {
        $generator = SuperRandom::for(FakeUser::class);

        $this->assertEquals('fake_users', $generator->table);
        $this->assertEquals('code', $generator->column);
    }

    /** @test */
    public function a_shortcut_for_length_is_present()
    {
        $generator = SuperRandom::length(123);

        $this->assertEquals(123, $generator->length);
    }

    /** @test */
    public function shortcuts_can_be_used()
    {
        $generator = SuperRandom::for('test.code')->length(18);

        $code = $generator->generate();

        $this->assertEquals(18, strlen($code));
        $this->assertEquals('test', $generator->table);
        $this->assertEquals('code', $generator->column);
    }

    /** @test */
    public function has_a_default_available_chars()
    {
        $chars = SuperRandom::getChars();

        $defaultChars = 'ABCDEFGHJKLMNPQRSTUVW23456789';

        $this->assertEquals($chars, $defaultChars);
    }

    /** @test */
    public function chars_can_be_set_in_the_config()
    {
        SuperRandom::setConfig([
            'chars' => 'abc123'
        ]);

        $this->assertEquals('abc123', SuperRandom::getChars());
    }

    /** @test */
    public function the_chars_are_in_the_code()
    {
        $code = SuperRandom::generate([
            'chars' => 'abc123'
        ]);

        collect(str_split($code))->each(function ($char) {
            $this->assertTrue(str_contains('abc123', $char));
        });
    }

    /** @test */
    public function they_are_unique()
    {
        $codes = array_map(function ($i) {
            return SuperRandom::generate();
        }, range(1, 200));

        $this->assertCount(200, array_unique($codes));
    }

    /** @test */
    public function cannot_contain_ambiguous_chars()
    {
        $code = SuperRandom::generate();

        $this->assertFalse(strpos($code, 'I'));
        $this->assertFalse(strpos($code, '1'));
        $this->assertFalse(strpos($code, 'O'));
        $this->assertFalse(strpos($code, '0'));
    }
}