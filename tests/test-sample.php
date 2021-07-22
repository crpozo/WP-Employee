<?php

class SampleTest extends \PHPUnit\Framework\TestCase {

	public function test_query_api()
	{

		$employee = new FeaturedEmployee();
		$response = $employee->queryEmployeeAPI();
		$expected = true;
		$this->assertEquals($expected,$response);
	}
}