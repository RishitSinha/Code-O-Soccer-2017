<?php

require_once('dbconfig.php');

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function register($teamname, $leadermail , $leadername , $leaderphone , $leadercollege , $memcount , $pass)
	{
                 echo $teamname . $leadermail . $leadername . $leaderphone . $leadercollege . $memcount . $pass; 
		try
		{
                        echo "start"; 
			$new_password = md5($pass);
echo "statement made";
			$stmt = $this->conn->prepare("INSERT INTO teams (team_name, leader_name , leader_mail , leader_phone , leader_college , mem_count , password) 
		                                               VALUES (:tname, :lname, :lmail, :lphone, :lcollege, :memcount, :pass)");
				
				echo "statement made";				  
			$stmt->bindparam(":tname", $teamname);
			$stmt->bindparam(":lname", $leadername);
			$stmt->bindparam(":lmail", $leadermail);
			$stmt->bindparam(":lphone", $leaderphone);
			$stmt->bindparam(":lcollege", $leadercollege);
			$stmt->bindparam(":memcount", $memcount);
			$stmt->bindparam(":pass", $new_password);
                        echo "statement made";
			$stmt->execute();
                         echo "done";
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	public function registerMember($tid , $MemberName , $MemberPhone , $MemberEmail)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO team_members (mem_name , mem_phone , mem_email , team_id) 
		                                               VALUES(:mname, :mphone, :mmail, :tid)");
												  
			$stmt->bindparam(":mname", $MemberName);
			$stmt->bindparam(":mphone", $MemberPhone);
			$stmt->bindparam(":mmail", $MemberEmail);
			$stmt->bindparam(":tid", $tid);
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function doLogin($tname,$lmail,$pass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM teams WHERE team_name=:tname OR leader_mail=:lmail ");
			$stmt->execute(array(':tname'=>$tname, ':lmail'=>$lmail));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(md5($pass)== $userRow['password'])
				{
					$_SESSION['user_session'] = $userRow['team_id'];
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	private function getMemberNumber($tid){
		$stmt = $this->conn->prepare("SELECT * FROM teams WHERE team_id = :tid");
		$stmt->execute(array(':tid'=>$tid));
		$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $userRow['mem_count'];
	}
	private function getMemberNumberReg($tid){
		$stmt = $this->conn->prepare("SELECT team_id FROM team_members WHERE team_id = :tid");
		$stmt->execute(array(':tid'=>$tid));
		$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
		return $stmt->rowCount();
	}

	public function createMemberTab($team_id){


		$count = $this -> getMemberNumber($team_id);

		for ($i=1; $i <= $count ; $i++) { 
			echo '
              <li><a href="#tab'.$i.'" class="active">Tab ',$i.'</a></li>
	        ';
		}
	}

	public function createMemberForm($team_id){

		$count = $this -> getMemberNumber($team_id);
		$reg_count = $this -> getMemberNumberReg($team_id);

		$stmt = $this->conn->prepare("SELECT * FROM team_members WHERE team_id = :tid");
		$stmt->execute(array(':tid'=>$team_id));
		

		for ($j=1; $j <= $reg_count ; $j++) {
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			echo '
		              <div id="tab'.$j.'" style="display:none;">
		                <h3 class="center-align">Member '.$j.'</h3>
		                <form method="post" action="index.php" class="mem-form">
		                  <div class="row">
		                    <div class="input-field col s12">
		                      <input disabled type="text" name="member-name" id="mem-'.$j.'-name">
		                      <label for="mem-'.$j.'-name">'.$userRow['mem_name'].'</label>
		                    </div>
		                    <div class="input-field col s12">
		                     <input disabled type="text" name="member-phone" id="mem-'.$j.'-phone">
		                     <label for="mem-'.$j.'-phone">'.$userRow['mem_email'].'</label>
		                    </div>
		                    <div class="input-field col s12">
		                      <input disabled type="text" name="member-email" id="mem-'.$j.'-email">
		                      <label for="mem-'.$j.'-email">'.$userRow['mem_phone'].'</label>
		                    </div>
		                    <button disabled type="submit" name="btn-mem-data" class="btn waves-effect waves-light center-position">Submit<i class="material-icons right">send</i></button>
		                  </div>
		                </form>
		              </div>
		            ';
		}
		$blank = $count;
		$init = $reg_count + 1;

		for ($i= $init; $i <= $blank ; $i++) {
			echo '
		              <div id="tab'.$i.'" style="display:none;">
		                <h3 class="center-align">Member '.$i.'</h3>
		                <form method="post" action="index.php" class="mem-form">
		                  <div class="row">
		                    <div class="input-field col s12">
		                      <input type="text" name="member-name" id="mem-'.$i.'-name">
		                      <label for="mem-'.$i.'-name">Name</label>
		                    </div>
		                    <div class="input-field col s12">
		                     <input type="text" name="member-phone" id="mem-'.$i.'-phone">
		                     <label for="mem-'.$i.'-phone">Phone Number</label>
		                    </div>
		                    <div class="input-field col s12">
		                      <input type="text" name="member-email" id="mem-'.$i.'-email">
		                      <label for="mem-'.$i.'-email">Email-ID</label>
		                    </div>
		                    <button type="submit" name="btn-mem-data" class="btn waves-effect waves-light center-position">Submit<i class="material-icons right">send</i></button>
		                  </div>
		                </form>
		              </div>
		            ';
		}
	}

	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
}
?>