<?php
/***************************** 
*
*   Info-W21- 3175- Lab 08
*   dbTriller.php
*   
******************************/
define("DB", "trills");
define("TB_USERS", DB . ".users");
define("TB_USERS_ID", TB_USERS . ".id");            
define("TB_USERS_NAME", TB_USERS . ".name");        
define("TB_USERS_PASS", TB_USERS . ".pass");
define("TB_USERS_SALT", TB_USERS . ".salt");
define("TB_POSTS", DB . ".posts");
define("TB_POSTS_ID", TB_POSTS . ".id");            
define("TB_POSTS_USER", TB_POSTS . ".user");        
define("TB_FOLLS", DB . ".followers");

class dbTriller
{
	private $host = "127.0.0.1";
    private $user = "Duc";             // your MySql user name
    private $pass = "Vu";            // your MySql passowrd
    private $database = DB;
    
    private $connection;
    private $connected = FALSE;
     
	function __construct()
	{
        date_default_timezone_set("America/Toronto");

        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->database);

        if( !$this->connection->connect_error ) $this->connected = TRUE;
    }
    function __destruct()
    {
    	if($this->connected) $this->connection->close();
    }
    function connected()
    {
    	return $this->connected;
    }

    function sign_up($name,$pass,$email)
    {
        $query1 = " INSERT into users (name, pass, plain_pass, email, salt) VALUES ('$name' , '$pass', '$pass', '$email','booshit');";
        $query2 = "SET @salt = uuid();";
        $query3 = "UPDATE users set pass = md5(concat('$pass' , @salt)), salt = @salt WHERE name = '$name';";

        if ($result1 = $this->connection->query($query1) && $result2 = $this->connection->query($query2) && $result3 = $this->connection->query($query3)) 
        {
            echo("<script> var note = 2; </script>"); //notify the sucess of creating the new user
             return 1;
        }
        else
        {
            
            return 2;
        }


    }

    function getUserId($name)
    {
        $query = "SELECT id FROM " . TB_USERS . " WHERE name = '$name'";

    	if ($result = $this->connection->query($query)) 
    	{
    		$row = $result->fetch_row();
            $result->close();
    		return $row[0];
    	}

    	return -1;
    }
    function getNoFollowers($name)
    {
    	$id = $this->getUserId($name);

        $query = "SELECT * FROM ". TB_FOLLS . " WHERE user = '$id' ";

    	if ($result = $this->connection->query($query)) 
    	{
            $count = $result->num_rows; 
            $result->close();
    		return $count; 
    	}

    	return -1;
    }
    function getNoFollowing($name)
    {
    	$id = $this->getUserId($name);

        $query = "SELECT * FROM ". TB_FOLLS . " WHERE follower = $id";

    	if ($result = $this->connection->query($query)) 
    	{
            $count = $result->num_rows; 
            $result->close();
            return $count; 
    	}

    	return -1;
    }
    function postTrill($name, $post)
    {
        $id = $this->getUserId($name);
        $now = date('Y-m-d H:i:s');

        $query = "INSERT INTO " . TB_POSTS . " (user, post, pdate) VALUES ($id, SUBSTRING('$post', 1, 280), '$now')";

        if ($result = $this->connection->query($query)) 
        {
            return $result;
        }

        return 0;
    }
    function removeTrill($id)
    {
        $query = "DELETE FROM " . TB_POSTS . " WHERE id = $id";

        if($result = $this->connection->query($query)) 
        {
            return $result;
        }

        return 0;
    }
    function getUserPosts()
    {
        $res = array();

    	$query = "SELECT " . TB_POSTS_ID . ", name, post, pdate FROM " . TB_USERS . ", ". TB_POSTS . " WHERE " . TB_USERS_ID . " = " . TB_POSTS_USER . " ORDER BY pdate DESC";

        $result = $this->connection->query($query);

    	if($result && $result->num_rows > 0) 
    	{
    		for($i = 0; $i < $result->num_rows; $i++) $res[] = $result->fetch_assoc();
    	}

        $result->close();
    	return $res;
    }
    function getUserSalt($user)
    {
        $query = "SELECT ". TB_USERS_SALT . " FROM " .  TB_USERS . " WHERE " . TB_USERS_NAME . " = '$user'";

        if($result = $this->connection->query($query)) 
        {
            $row = $result->fetch_row();
            $salt = $row[0];
            $result->close();
            return $salt;
        }

        return NULL;
    }
    function authenticate($no, $user, $pass)
    {
        switch($no) 
        {
            case 1:
                return $this->authenticate1($user, $pass); // the STRONGEST
            case 2:
                return $this->authenticate2($user, $pass);
            case 3:
                return $this->authenticate3($user, $pass);
            default:
               return NULL;
        }
    }
    function authenticate1($user, $pass)
    {
        $salt = $this->getUserSalt($user);
        if(!$salt) return NULL; // nếu ko kiếm đc salt
        
        $query = "SELECT " . TB_USERS_NAME . "," . TB_USERS_PASS . "," . TB_USERS_SALT .
                 " FROM " . TB_USERS . 
                 " WHERE " . TB_USERS_PASS . " = md5(concat('$pass', '$salt'))";

        if($result = $this->connection->query($query))  // nếu kiếm đc cái match hash đó ... yeah :))
        {
            $res[] = $result->fetch_assoc();
            $result->close();
            return $res[0];
        }

        return NULL;
    }
    function authenticate2($user, $pass)
    {
        $salt = $this->getUserSalt($user);
        if(!$salt) return NULL;

        $query = $this->connection->prepare("SELECT " . TB_USERS_NAME . "," . TB_USERS_PASS . "," . TB_USERS_SALT . " FROM " . TB_USERS .  " WHERE " . TB_USERS_PASS . " =?");
        $param = md5($pass . $salt);
        $query->bind_param("s", strval($param));
        $query->execute();

        if($result = $query->get_result()) 
        {
            $res[] = $result->fetch_assoc();
            $result->close();
            
            return $res[0];
        }

        return NULL;
    }
    function authenticate3($user, $pass)
    {
        $query = "SELECT " . TB_USERS_NAME . "," . TB_USERS_PASS . "," . TB_USERS_SALT .
                 " FROM " . TB_USERS . 
                 " WHERE " . TB_USERS_PASS . 
                 " = (md5(concat('$pass', 
                 (SELECT ". TB_USERS_SALT . " FROM " .  TB_USERS . " WHERE " . TB_USERS_NAME . " = '$user'))))";

        if($result = $this->connection->query($query)) 
        {
            $res[] = $result->fetch_assoc();
            $result->close();
            return $res[0];
        }

        return NULL;
    }
}
?>





