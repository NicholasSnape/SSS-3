<?php

interface IDatabase {

	function select($parameters);

	function insert($parameters);

	function update($parameters);

	function delete($parameters);

}