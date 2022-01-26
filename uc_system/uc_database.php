<?php
	/**
	 * uCrew database code.
	 */
	class uCrewDatabase extends uCrewConfiguration {
		// Connection object
		private $connection;
		// Construct class function
		function __construct() {
			// Connect to database
			$this->connection = new mysqli($this->database["server"], $this->database["user"], $this->database["password"], $this->database["database"]);
			// Set encoding (charset)
			$this->connection->set_charset("utf8");
			// If connection falied, go down
			if ($this->connection->connect_error) {
  				die("Database connection error: <b>" . $this->connection->connect_error . "</b>");
			}
		}
		// Get data from database
		public function getData($query){
			// Do query
			$result = $this->connection->query($query);
			// Check result and return
			if ($result->num_rows > 0) {
				return $result->fetch_assoc();
			}else{
				return 0;
			}
		}
		// Destruct class function
		function __destruct() {
			// Close connection
       		$this->connection->close();
   		}
	}
?>