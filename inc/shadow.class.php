<?php
class Shadow {
	private $connection;


    function __construct($mysqli){
        $this->connection = ($mysqli);
    }

	function getAllData($keyword="") {
		if ($keyword == "") {
			$search = "%%";
		}else{
			$search = "%".$keyword."%";
		}

			$stmt = $this->connection->prepare("SELECT id, name, description, company, county, parish, location, address, link, inserted, active, email, number FROM shadow_offers WHERE active IS NOT NULL AND deleted IS NULL AND (name LIKE ? OR description LIKE ? OR company LIKE ? OR county LIKE ? OR parish LIKE ? OR location LIKE ? OR address LIKE ? OR email LIKE ? OR number LIKE ?) ORDER BY inserted DESC");

			$stmt->bind_param("sssssssss", $search, $search, $search, $search, $search, $search, $search, $search, $search);
			$stmt->bind_result($id_from_db, $name_from_db, $desc_from_db, $company_from_db, $county_from_db, $parish_from_db, $location_from_db, $address_from_db, $link, $inserted_from_db, $active_from_db, $email_from_db, $number_from_db);
			$stmt->execute();

			$array = array();
			while($stmt->fetch()) {
				$job = new StdClass();
				$job->id = $id_from_db;
				$job->name = $name_from_db;
				$job->description = $desc_from_db;
				$job->company = $company_from_db;
				$job->county = $county_from_db;
				$job->parish = $parish_from_db;
				$job->location = $location_from_db;
				$job->address = $address_from_db;
				$job->link = $link;
				$job->inserted = $inserted_from_db;
				$job->active = $active_from_db;
				$job->email = $email_from_db;
				$job->number = $number_from_db;
				array_push($array, $job);
		}
			return $array;
			echo($name_from_db);


		$stmt->close();

	}

	function getAdminData() {


			$stmt = $this->connection->prepare("SELECT id, name, description, company, county, parish, location, address, link, inserted, active, email, number FROM shadow_offers WHERE deleted IS NULL ORDER BY id");
			$stmt->bind_result($id_from_db, $name_from_db, $desc_from_db, $company_from_db, $county_from_db, $parish_from_db, $location_from_db, $address_from_db, $link, $inserted_from_db, $active_from_db, $email_from_db, $number_from_db);
			$stmt->execute();

			$array = array();
			while($stmt->fetch()) {
				$job = new StdClass();
				$job->id = $id_from_db;
				$job->name = $name_from_db;
				$job->description = $desc_from_db;
				$job->company = $company_from_db;
				$job->county = $county_from_db;
				$job->parish = $parish_from_db;
				$job->location = $location_from_db;
				$job->address = $address_from_db;
				$job->link = $link;
				$job->inserted = $inserted_from_db;
				$job->active = $active_from_db;
				$job->email = $email_from_db;
				$job->number = $number_from_db;
				array_push($array, $job);
		}
			return $array;
			echo($name_from_db);


		$stmt->close();

	}


	function createJob($job_company, $job_name, $job_desc, $job_county, $job_parish, $job_location, $job_address, $email, $number) {

	$response = new StdClass();

	$stmt = $this->connection->prepare("SELECT id FROM shadow_offers WHERE name=? AND description=? AND company=? AND address=? AND deleted IS NULL");
	$stmt->bind_param("ssss", $job_name, $job_desc, $job_company, $job_address);
	$stmt->bind_result($id);
	$stmt->execute();

	if($stmt->fetch()) {

		$error = new StdClass();
		$error->id = 0;
		$error->message = "Olete juba täpselt samade andmetega töö sisestanud!";
		$response->error = $error;

		return $response;
	}
	$stmt->close();

			$stmt = $this->connection->prepare("INSERT INTO shadow_offers (company, name, email, number, description, county, parish, location, address, inserted, active) VALUES (?,?,?,?,?,?,?,?,?,NOW(),NOW())");
			$stmt->bind_param("sssssssss", $job_company, $job_name, $email, $number, $job_desc, $job_county, $job_parish, $job_location, $job_address);

	if($stmt->execute()) {
		$success = new StdClass();
		$success->id = 0;
		$success->message = "Vari on edukalt sisestatud!";
		$response->success = $success;

		return $response;
	}

			$stmt->close();

	}

	function deleteJobData($job_id) {

		$stmt = $this->connection->prepare("UPDATE shadow_offers SET deleted=NOW() WHERE id=? ");
		$stmt->bind_param("i", $job_id);
		$stmt->execute();
		//Tühjendame aadressirea
		header("Location: editshadows.php");

		$stmt->close();

	}

	function updateJobData($job_id, $job_name, $job_email, $job_number, $job_company, $job_desc, $job_county, $job_parish, $job_location, $job_address) {

		$stmt = $this->connection->prepare("UPDATE shadow_offers SET name=?, email=?, number=?, description=?, company=?, county=?, parish=?, location=?, address=? WHERE id=?");
		$stmt->bind_param("sssssssssi", $job_name, $job_email, $job_number, $job_desc, $job_company, $job_county, $job_parish, $job_location, $job_address, $job_id);

		$stmt->execute();
		//Tühjendame aadressirea

		$stmt->close();

	}


	function activateData($job_id) {

		$stmt = $this->connection->prepare("UPDATE shadow_offers SET active=NOW() WHERE id=?");
		$stmt->bind_param("i", $job_id);

		$stmt->execute();
		header("Location: editshadows.php");

		$stmt->close();

	}

	function deactivateData($job_id) {

		$stmt = $this->connection->prepare("UPDATE shadow_offers SET active=NULL WHERE id=?");
		$stmt->bind_param("i", $job_id);

		$stmt->execute();
		header("Location: editshadows.php");

		$stmt->close();

	}




}
?>
