<?php
#Database Class...

class DB_Wrapper {
	var $link_id;
	var $query_result;
	var $error_no;
	var $error_msg;

	#Construcor
	function DB_Wrapper( $hostname, $username, $password, $databasename ) 
	{
		$this->link_id = @mysqli_connect($hostname, $username, $password, $databasename);
		
		if ( !$this->link_id ) {
			die( json_encode( array( "error" => mysqli_connect_error() ) ) );
		}
		
		return $this->link_id;
	}

	#Query
	function query( $sql )
	{
		$this->query_result = @mysqli_query( $this->link_id, $sql );
		
		if ( $this->query_result )
		{
			return $this->query_result;
		}
		
		$this->error_no = @mysqli_errno( $this->link_id );
		$this->error_msg = @mysqli_error( $this->link_id );
		return false;
	}
	
	function fetch_assoc( $id = 0 )
	{ 
	    return $id ? @mysqli_fetch_assoc( $id ) : false; 
	}

	function fetch_array( $id = 0 )
	{
	    return $id ? @mysqli_fetch_array( $id ) : false;
	}

	function fetch_row( $id = 0 )
	{
	    return $id ? @mysqli_fetch_row( $id ) : false;
	}

	function num_rows( $id = 0 )
	{
	    return $id ? @mysqli_num_rows( $id ) : false;
	}

	function affected_rows( $id = 0 )
	{
	    return $id ? @mysqli_affected_rows( $id ) : false;
	}

	function insert_id()
	{
	    return $this->link_id ? @mysqli_insert_id( $this->link_id ) : false;
	}
	
	#Escape String
	function escape( $s )
	{
		if ( is_array( $s ) )
			return '';
		return mysqli_real_escape_string( $this->link_id, $s );
	}
	
	#Error
	function error()
	{
		if ($this->error_no)
		{
			$result['error_no'] = $this->error_no;
			$result['error_msg'] = $this->error_msg;
			return $result;
		}
		return false;
	}
	
	function close()
	{
		#Sanity
		if ( !$this->link_id )
			return false;
		
		#Free Result
		if ( $this->query_result )
			@mysqli_free_result( $this->query_result );
		
		#Close
		return @mysqli_close( $this->link_id );
	}

}

?>
