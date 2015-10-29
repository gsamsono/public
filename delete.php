<?php
include "top.php";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables

// SECTION: 1a.
// variables for the classroom purposes to help find errors.

$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;

//$registerIdERROR = false;

if (isset($_POST["btnFindUser"])){
    
    $email = filter_var($_POST["hdnEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;

    $password = htmlentities($_POST["hdnPassword"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $password;
    
    if (($email != "") && !verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    } if($email === "") {
        $errorMsg[] = "Please enter your email address.";
        $emailERROR = true;
    } if($password === "") {
        $errorMsg[] = "Please enter your password.";
        $passwordERROR = true;
    }
}

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form

//need to get these from the database once the user enters their email address
/** $firstName = "grace";
$lastName = "samsonow";
$email = "gsamsono@uvm.edu";
$phone = "8021231234";
$address = "12 jahsk";
$city = "burl";
$state = "vt";
$zip = "05401";
$bio = "kjhaskdga";
$password = "test"; */
//$registerId = "4";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.

$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;
$phoneERROR = false;
$addressERROR = false;
$cityERROR = false;
$stateERROR = false;
$zipERROR = false;
$bioERROR = false;
$passwordERROR = false;
$registerIdERROR = false;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// array used to hold form values that will be written to a CSV file
//$dataRecord = array();

$mailed=false; // have we mailed the information to the user?
$messageA = "";
$messageB = "";
$messageC = "";
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnDelete"])) {

if($debug){
    //print "<p> id= " . $registerId . "</p>"; 
    print "<p> email = " . $email . "</p>";
}

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2a Security
    /**
    if (!securityCheck(true)) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }*/
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2b Sanitize (clean) data 
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order as declared in section 1c.

$email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
            $dataRecord[] = $email;
$password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
            $dataRecord[] = $password;

    $firstName = htmlentities($_POST["txtFirstname"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;

    $lastName = htmlentities($_POST["txtLastname"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $lastName;

$phone = htmlentities($_POST["txtPhone"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $phone;

$address = htmlentities($_POST["txtAddress"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $address;

$city = htmlentities($_POST["txtCity"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $city;

$state = htmlentities($_POST["txtState"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $state;

$zip = htmlentities($_POST["txtZip"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $zip;

$bio = htmlentities($_POST["txtBio"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $bio;

$email = filter_var($_POST["hdnEmail"], FILTER_SANITIZE_EMAIL);
$password = htmlentities($_POST["hdnPassword"], ENT_QUOTES, "UTF-8");
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    // SECTION: 2c Validation
    // Validation section. Check each value for possible errors, empty or
    // not what we expect. You will need an IF block for each element you will
    // check (see above section 1c and 1d). The if blocks should also be in the
    // order that the elements appear on your form so that the error messages
    // will be in the order they appear. errorMsg will be displayed on the form
    // see section 3b. The error flag ($emailERROR) will be used in section 3c.

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    // SECTION: 2d Process Form - Passed Validation
    // Process for when the form passes validation (the errorMsg array is empty)

    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        // SECTION: 2e Save Data
        // This block saves the data in the database.

        $primaryKey = "";
        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();        

$q1 = "SELECT fldFirstname, fldLastname, fldEmail, fldPhone, fldAddress, fldCity, ";
$q1 .= "fldState, fldZip, fldBio, fldPassword, pmkRegisterId ";
$q1 .= "FROM tblRegister WHERE (fldEmail LIKE ?) AND (fldPassword LIKE ?) ";
$data = array($email, $password);
if ($debug) {
    print "<p>q1: " . $q1 . "</p>";
    print "<p>print_r(data): " . print_r($data) . "</p>";
    }
$results = $thisDatabase->select($q1, $data);
        
        $message = '<h1>Your deleted information:</h1>';
        foreach ($_POST as $key => $value) {
            if(($key != "btnDelete") && ($key != "btnFindUser")){
                $message .= "<p>";
                $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));
                //print "<p>key: " . $key . "</p>";
                //print "<p>value: " . $value . "</p>";
                //print "<p>camelCase: " . $camelCase . "</p>";
                foreach ($camelCase as $one) {
                    $message .= $one . " ";
                }
                $message .= ":  " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
            } //ends if($key != "btnSubmit")
        } //ends foreach loop

$query = "DELETE FROM tblRegister WHERE (fldEmail LIKE ?) AND (fldPassword LIKE ?) ";
            $data = array($email, $password);
            
	if ($debug) {
                print "<p>query: " . $query . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
            }
	$results = $thisDatabase->select($query, $data);

            $primaryKey = $registerId;
            if ($debug){
                print "<p>pmk ($ registerId) = " . $primaryKey . "</p>";
                print "<p> results = " . $results . "</p>"; 
                print "<p> print_r(results) = " . print_r($results) . "</p>"; 
            }
            
// all sql statements are done so lets commit to our changes
            $dataEntered = $thisDatabase->db->commit();
            $dataEntered = true;
            if ($debug)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabase->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accepting your data.";
        }
        // If the transaction was successful, give success message
        if ($dataEntered) {
            if ($debug)
                print "<p>data entered now prepare keys ";

            //#################################################################
            // create a key value for confirmation

            $query = "SELECT fldDateJoined FROM tblRegister WHERE fldEmail LIKE ? ";//pmkRegisterId = " . $primaryKey;
            $data = array($email);
            $results = $thisDatabase->select($query, $data);

            $dateSubmitted = $results[0]["fldDateJoined"];

            $key1 = sha1($dateSubmitted);
            $key2 = $primaryKey;

            if ($debug) {
                print "<p>key 1: " . $key1;
                print "<p>key 2: " . $key2;
                print "<p> results = " . $results . "</p>"; }

//#################################################################
//Put forms information into a variable to print on the screen

            $messageA = '<h1>Your account information has been deleted.</h1>';
            $message =  "<p>This message is to notify you that all account information ";
            $message .=  "associated with the email address '" . $email . "' has ";
            $message .= "been deleted from our website.</p>";

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2g Mail to user
        //
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $email; // the person who filled out the form
        $cc = "";
        $bcc = "";
        $from = "Pet Search Website <noreply@petsearch.com>";

        // subject of mail should make sense to your form
        $todaysDate = strftime("%x");
        $subject = "Notification of Account Deletion";

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $messageA . $messageB . $messageC . $message);
       } //data entered  
    } // end form is valid
    
} // ends if form was submitted.

//#############################################################################
//
// SECTION 3 Display Form
//
?>

<article id="main">

    <?php
    //####################################
    // SECTION 3a.
    // If its the first time coming to the form or there are errors we are going
    // to display the form.
    if (isset($_POST["btnDelete"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print "<h1>Your account has ";
        if (!$mailed) {
            print "NOT ";
        }
        print "been deleted. </h1>";
        if ($mailed) {
            print $message;
        }
    } else {


        //####################################
        //
        // SECTION 3b Error Messages
        //
        // display any error messages before we print out the form

        if ($errorMsg) {
            print '<div id="errors">';
            print "<ol>\n";
            foreach ($errorMsg as $err) {
                print "<li>" . $err . "</li>\n";
            }
            print "</ol>\n";
            print '</div>';
        }


        //####################################
        //
        // SECTION 3c html Form
        //
        /* Display the HTML form. note that the action is to this same page. $phpSelf
          is defined in top.php
          NOTE the line:

          value="<?php print $email; ?>

          this makes the form sticky by displaying either the initial default value (line 35)
          or the value they typed in (line 84)

          NOTE this line:

          <?php if($emailERROR) print 'class="mistake"'; ?>

          this prints out a css class so that we can highlight the background etc. to
          make it stand out that a mistake happened here.

         */
        ?>

        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">

            <fieldset class="wrapper">
                <legend>Delete your account</legend>

<?php if (!isset($_POST["btnFindUser"])) { ?>
                <fieldset class="wrapperTwo">
                    <legend>Please enter your email address and password so we can bring up your current account information: </legend>

			<label for="txtEmail" class="required">Email
                            <input type="text" id="txtEmail" name="txtEmail"
                                   value="<?php print $email; ?>"
                                   tabindex="50" maxlength="45" placeholder="The email address that you registered with:"
                                   <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>
                        <!--<legend>Password Information</legend>-->
                        <label for="txtPassword" class="required">Password
                            <input type="text" id="txtPassword" name="txtPassword"
                                   value="<?php print $password; ?>"
                                   tabindex="55" maxlength="45" placeholder="Enter your password"
				   <?php if ($passwordERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()" autofocus>
			</label><!-- ends password -->
                            
                            <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnFindUser" name="btnFindUser" value="Find User" tabindex="60" class="button">
                </fieldset> 
         
<p>New User?</p>
<p>Please register <a href="register.php">here</a> before deleting a pet!</p>    
<?php 
} //end if (!isset($_POST["btnFindUser"]))
else{  //if btnFindUser was pressed

    //make sure email and password match
$email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
$dataRecord[] = $email;
$password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
$dataRecord[] = $password;

if (($email != "") && !verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
} if($email === "") {
        $errorMsg[] = "Please enter your email address.";
        $emailERROR = true;
} if($password === "") {
        $errorMsg[] = "Please enter your password.";
        $passwordERROR = true;
}

//*************************************************************************************/
    //find the users id # and password from their email
    $q = "SELECT pmkRegisterId, fldEmail, fldPassword FROM tblRegister WHERE (fldEmail LIKE ?) AND (fldPassword LIKE ?) ";
    $dat = array($email, $password);
    
    if ($debug) {
        print "<p>q: " . $q . "</p>";
        print "<p>print_r(dat): " . print_r($dat) . "</p>";
    }
    
    $prefill = $thisDatabase->select($q, $dat);
    if ($debug){
        print "<p>print_r(prefill) # of items in array: " . print_r($prefill) . "</p>";
    }
    
    foreach ($prefill as $row) {
        $registerId = $row["pmkRegisterId"];
	$password = $row["fldPassword"];
    }//ends for each loop
    
    if ($registerId == ""){ //so if registerId IS EMPTY (email doesnt exist in db)
        if($debug) {
            print "registerId is empty...";
        }
        print "<p>Your email address and password combination were not found.";
        $registerIdERROR = true;
        ?>
        <p>Try again <a href="delete.php">here</a>.</p>
        <p>Or if you are a new user, please register <a href="register.php">here</a>.</p>
        <?php 
    } else {
        if ($debug){
            print "registerId is not empty...";
            print "<p>registerId: " . $registerId . "</p>";
        }
        
        
$q = "SELECT fldFirstname, fldLastname, fldEmail, fldPhone, fldAddress, fldCity, ";
$q .= "fldState, fldZip, fldBio, fldPassword, pmkRegisterId ";
$q .= "FROM tblRegister WHERE (fldEmail LIKE ?) AND (fldPassword LIKE ?) ";
$data = array($email, $password);
if ($debug) {
    print "<p>q: " . $q . "</p>";
    print "<p>print_r(data): " . print_r($data) . "</p>";
    }
$prefill = $thisDatabase->select($q, $data);

foreach ($prefill as $row) {
	$firstName = $row["fldFirstname"];
	$lastName = $row["fldLastname"];
	$email = $row["fldEmail"];
	$phone = $row["fldPhone"];
	$address = $row["fldAddress"];
	$city = $row["fldCity"];
	$state = $row["fldState"];
	$zip = $row["fldZip"];
	$bio = $row["fldBio"];
	$password = $row["fldPassword"];
        $registerId = $row["pmkRegisterId"];
}//ends for each loop
                        
                   //display the users info
                    ?> <fieldset class="search">
                        <legend>Your current account information:</legend>

                        <input type="hidden" id="hdnEmail" name="hdnEmail" value="<?php print $email; ?>">
                        <input type="hidden" id="hdnRegisterId" name="hdnRegisterId" value="<?php print $registerId; ?>">
			<input type="hidden" id="hdnPassword" name="hdnPassword" value="<?php print $password; ?>">
                        <?php if($debug) {
				print "<p> YOUR PASSWORD: " . $password . "</p>"; 
                              } ?>
                        
<p>First name: <?php print $firstName; ?> 
<p>Last name: <?php print $lastName; ?> </p>
<p>Email address: <?php print $email; ?> </p>
<p>User ID: <?php print $registerId; ?> </p>
<p>Phone number: <?php print $phone; ?> </p>
<p>Street address: <?php print $address; ?> </p>
<p>City: <?php print $city; ?> </p>
<p>State: <?php print $state; ?> </p>
<p>Zip code: <?php print $zip; ?> </p>
<p>Personal bio: <?php print $bio; ?> </p>
<p>Password: <?php print $password; ?> </p>

                    </fieldset> <!-- ends contact -->
                </fieldset> <!-- ends wrapper Two -->
                
                <fieldset class="buttons">
                    <legend>Click this button to permanently delete your account.</legend>
                    <input type="submit" id="btnDelete" name="btnDelete" value="Delete" tabindex="900" class="button">
                </fieldset> <!-- ends buttons -->
                
            </fieldset> <!-- Ends Wrapper -->
        </form>


<?php
} //ends if registerId IS NOT empty
} } // end body submit    
?>

</article>
<aside id="other">
</aside> 

<?php include "footer.php"; ?>

</body>
</html>

