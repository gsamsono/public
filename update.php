<?php
include "top.php";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables

// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = false;//true;

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

if (isset($_POST["btnFindUser"])){

    $email = filter_var($_POST["hdnEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;

    if (($email != "") && !verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    }
    if($email === "") {
        $errorMsg[] = "Please enter your email address.";
        $emailERROR = true;
    }
}//ends if (isset($_POST["btnFindUser"]))

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

$mailed=false; // have we mailed the information to the user?
$messageA = "";
$messageB = "";
$messageC = "";
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {
if ($debug){
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

    $firstName = htmlentities($_POST["txtFirstname"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;

    $lastName = htmlentities($_POST["txtLastname"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $lastName;

    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;

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

$password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $password;

$email = filter_var($_POST["hdnEmail"], FILTER_SANITIZE_EMAIL);


    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2c Validation
    //
    // Validation section. Check each value for possible errors, empty or
    // not what we expect. You will need an IF block for each element you will
    // check (see above section 1c and 1d). The if blocks should also be in the
    // order that the elements appear on your form so that the error messages
    // will be in the order they appear. errorMsg will be displayed on the form
    // see section 3b. The error flag ($emailERROR) will be used in section 3c.

        if (($firstName != "") && !verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to be incorrect.";
        $firstNameERROR = true;
    }
        if (($lastName != "") && !verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your last name appears to be incorrect.";
        $lastNameERROR = true;
    }
        if (($email != "") && !verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    }
        if($email === "") {
        $errorMsg[] = "Please enter your email address.";
        $emailERROR = true;
    }
        if (($phone != "") && !verifyNumeric($phone)) {
        $errorMsg[] = "Your phone number appears to be incorrect.";
        $phoneERROR = true;
    }
        if (($address != "") && !verifyAlphaNum($address)) {
        $errorMsg[] = "Your address appears to be incorrect.";
        $addressERROR = true;
    }
        if (($city != "") && !verifyAlphaNum($city)) {
        $errorMsg[] = "Your city appears to be incorrect.";
        $cityERROR = true;
    }
        if (($state != "") && !verifyAlphaNum($state)) {
        $errorMsg[] = "Your state appears to be incorrect.";
        $stateERROR = true;
    }
        if (($zip != "") && !verifyNumeric($zip)) {
        $errorMsg[] = "Your zip code appears to be incorrect.";
        $zipERROR = true;
    }
	if (($registerId != "") && !verifyNumeric($registerId)) {
        $errorMsg[] = "Your register ID appears to be incorrect.";
        $registerIdERROR = true;
    }

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2d Process Form - Passed Validation
    //
    // Process for when the form passes validation (the errorMsg array is empty)
    //
    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2e Save Data
        //
        // This block saves the data in the database.

        $primaryKey = "";
        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();
            
        if ($firstName != "") {
        //for when the user wants to change their first name
	$q2 = "UPDATE tblRegister SET fldFirstname = ? WHERE fldEmail LIKE ? ";//pmkRegisterId = ?";
	$data = array($firstName, $email);//$registerId);
if ($debug) {
                print "<p>sql: " . $q2 . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
            }
	$results = $thisDatabase->update($q2, $data);

            }if ($lastName != "") {
        //for when the user wants to change their last name
	$q2 = "UPDATE tblRegister SET fldLastname = ? WHERE fldEmail LIKE ? ";//pmkRegisterId = ?";
	$data = array($lastName, $email);//$registerId);
if ($debug) {
                print "<p>sql: " . $q2 . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
            }
	$results = $thisDatabase->update($q2, $data);

            }if ($phone != "") {
        //for when the user wants to change their phone number
	$q2 = "UPDATE tblRegister SET fldPhone = ? WHERE fldEmail LIKE ? ";//pmkRegisterId = ?";
	$data = array($phone, $email);//$registerId);
if ($debug) {
                print "<p>sql: " . $q2 . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
            }
	$results = $thisDatabase->update($q2, $data);

            }if ($address != "") {
        //for when the user wants to change their address
	$q2 = "UPDATE tblRegister SET fldAddress = ? WHERE fldEmail LIKE ? ";//pmkRegisterId = ?";
	$data = array($address, $email);//$registerId);
if ($debug) {
                print "<p>sql: " . $q2 . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
            }
	$results = $thisDatabase->update($q2, $data);

            }if ($city != "") {
        //for when the user wants to change their city
	$q2 = "UPDATE tblRegister SET fldCity = ? WHERE fldEmail LIKE ? ";//pmkRegisterId = ?";
	$data = array($city, $email);//$registerId);
if ($debug) {
                print "<p>sql: " . $q2 . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
            }
	$results = $thisDatabase->update($q2, $data);

            }if ($state != "") {
        //for when the user wants to change their state
	$q2 = "UPDATE tblRegister SET fldState = ? WHERE fldEmail LIKE ? ";//pmkRegisterId = ?";
	$data = array($state, $email);//$registerId);
if ($debug) {
                print "<p>sql: " . $q2 . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
            }
	$results = $thisDatabase->update($q2, $data);

            }if ($zip != "") {
        //for when the user wants to change their zip code
	$q2 = "UPDATE tblRegister SET fldZip = ? WHERE fldEmail LIKE ? ";//pmkRegisterId = ?";
	$data = array($zip, $email);//$registerId);
if ($debug) {
                print "<p>sql: " . $q2 . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
            }
	$results = $thisDatabase->update($q2, $data);

            }if ($bio != "") {
        //for when the user wants to change their bio
	$q2 = "UPDATE tblRegister SET fldBio = ? WHERE fldEmail LIKE ? ";//pmkRegisterId = ?";
	$data = array($bio, $email);//$registerId);
if ($debug) {
                print "<p>sql: " . $q2 . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
            }
	$results = $thisDatabase->update($q2, $data);

            }if ($password != "") {
        //for when the user wants to change their password
	$q2 = "UPDATE tblRegister SET fldPassword = ? WHERE fldEmail LIKE ? ";//pmkRegisterId = ?";
	$data = array($password, $email);//$registerId);
if ($debug) {
                print "<p>sql: " . $q2 . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
            }
	$results = $thisDatabase->update($q2, $data);
            } 

//************************************************************************************************
            $primaryKey = $registerId; //$thisDatabase->lastInsert();
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

            $query = "SELECT fldDateJoined FROM tblRegister WHERE pmkRegisterId = " . $primaryKey;
            $results = $thisDatabase->select($query);

            $dateSubmitted = $results[0]["fldDateJoined"];

            $key1 = sha1($dateSubmitted);
            $key2 = $primaryKey;

            if ($debug) {
                print "<p>key 1: " . $key1 . "</p>";
                print "<p>key 2: " . $key2 . "</p>";
                print "<p> results = " . $results . "</p>"; }

 //#################################################################
            //Put forms information into a variable to print on the screen

            $messageA = '<h1>Thank you for updating your information.</h1>';
            
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2f Create message
        //
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g).

        $message = '<h2>Your updated information:</h2>';

        foreach ($_POST as $key => $value) {
            
            if(($key != "btnSubmit") && ($key != "btnFindUser")){
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
        $subject = "Database updated information: " . $firstName . " " . $lastName;

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
    
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print "<h1>Your account information has ";

        if (!$mailed) {
            print "NOT ";
        }

        print "been updated. </h1>";

        print "<p>A copy of your account information has ";
        if (!$mailed) {
            print "NOT ";
        }
        print "been sent to your email address.";

        print $message;
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
          make it stand out that a mistake happened here. */
?>

        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">

            <fieldset class="wrapper">
                <legend>Update Your Information</legend>

<?php if (!isset($_POST["btnFindUser"])) { ?>
                <fieldset class="wrapperTwo">
                    <legend>Please enter your email address so we can bring up your current account information: </legend>

                    <!-- <label for="txtRegisterId" class="required">User ID
                            <input type="text" id="txtRegisterId" name="txtRegisterId"
                                   value="<?php print $registerId; ?>"
                                   tabindex="50" maxlength="45" placeholder="example: 6"
                                   <?php if ($registerIdERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label> -->
			<label for="txtEmail" class="required">Email
                            <input type="text" id="txtEmail" name="txtEmail"
                                   value="<?php print $email; ?>"
                                   tabindex="50" maxlength="45" placeholder="The email address that you registered with:"
                                   <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>
                            
                            <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnFindUser" name="btnFindUser" value="Find User" tabindex="60" class="button">
                </fieldset> 
         
<?php 
} //end if (!isset($_POST["btnFindUser"]))
else{  //if btnFindUser was pressed

$email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
$dataRecord[] = $email;

if (($email != "") && !verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    }

$q = "SELECT fldFirstname, fldLastname, fldEmail, fldPhone, fldAddress, fldCity, ";
$q .= "fldState, fldZip, fldBio, fldPassword, pmkRegisterId ";
$q .= "FROM tblRegister WHERE fldEmail LIKE ? ";// . $email;//pmkRegisterId = " . $registerId;
$data = array($email);
$prefill = $thisDatabase->select($q, $data);

if ($debug){
    print "<p>q: " . $q . "</p>";
    print "<p>print_r(data): " . print_r($data) . "</p>";
}

foreach ($prefill as $row) {
	$firstName = $row["fldFirstname"];
	$lastName = $row["fldLastname"];
	//$email = $row["fldEmail"];
	$phone = $row["fldPhone"];
	$address = $row["fldAddress"];
	$city = $row["fldCity"];
	$state = $row["fldState"];
	$zip = $row["fldZip"];
	$bio = $row["fldBio"];
	$password = $row["fldPassword"];
        $registerId = $row["pmkRegisterId"];
}//ends for each loop
                        
                   //display the fields the user can change
                    ?> <fieldset class="search">
                        <legend>You may update as many or as few fields as needed</legend>
                        
                        <input type="hidden" id="hdnEmail" 
				name="hdnEmail" value="<?php print $email; ?>">

                        <?php print "<p> Your email address: " . $email . "</p>"; ?>
                        <?php print "<p> Your unique user ID: " . $registerId . "</p>"; ?>

                        <label for="txtFirstname" class="required">First Name
                            <input type="text" id="txtFirstname" name="txtFirstname"
                                   value="<?php print $firstName; ?>"
                                   tabindex="100" maxlength="100" placeholder="Enter your first name"
                                   <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>
                        
			<label for="txtLastname" class="required">Last Name
                            <input type="text" id="txtLastname" name="txtLastname"
                                   value="<?php print $lastName; ?>"
                                   tabindex="110" maxlength="100" placeholder="Enter your last name"
                                   <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>

                        <!-- <label for="txtEmail" class="required">Email
                            <input type="text" id="txtEmail" name="txtEmail"
                                   value="<?php print $email; ?>"
                                   tabindex="120" maxlength="45" placeholder="Enter a valid email address like: person@yahoo.com"
                                   <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label> -->

			<label for="txtPhone" class="required">Phone
                            <input type="text" id="txtPhone" name="txtPhone"
                                   value="<?php print $phone; ?>"
                                   tabindex="130" maxlength="45" placeholder="Enter a valid phone number like: 8021231234"
                                   <?php if ($phoneERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>

			<label for="txtAddress" class="required">Street Address
                            <input type="text" id="txtAddress" name="txtAddress"
                                   value="<?php print $address; ?>"
                                   tabindex="140" maxlength="45" placeholder="Example: 15 Main Street"
                                   <?php if ($addressERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>

			<label for="txtCity" class="required">City
                            <input type="text" id="txtCity" name="txtCity"
                                   value="<?php print $city; ?>"
                                   tabindex="150" maxlength="45" placeholder=""
                                   <?php if ($cityERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>

			<label for="txtState" class="required">State
                            <input type="text" id="txtState" name="txtState"
                                   value="<?php print $state; ?>"
                                   tabindex="160" maxlength="45" placeholder=""
                                   <?php if ($stateERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>

			<label for="txtZip" class="required">Zip Code
                            <input type="text" id="txtZip" name="txtZip"
                                   value="<?php print $zip; ?>"
                                   tabindex="170" maxlength="45" placeholder="Enter a valid zip code like: 05401"
                                   <?php if ($zipERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>

			<label for="txtBio" class="required">Bio
                            <input type="text" id="txtBio" name="txtBio"
                                   value="<?php print $bio; ?>"
                                   tabindex="180" maxlength="45" placeholder="About you..."
                                   <?php if ($bioERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>

			<label for="txtPassword" class="required">Password
                            <input type="text" id="txtPassword" name="txtPassword"
                                   value="<?php print $password; ?>"
                                   tabindex="190" maxlength="45" placeholder="Create your password"
                                   <?php if ($passwordERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>
			
                    </fieldset> <!-- ends contact -->
                    
                </fieldset> <!-- ends wrapper Two -->
                
                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Update" tabindex="900" class="button">
                </fieldset> <!-- ends buttons -->
                
            </fieldset> <!-- Ends Wrapper -->
        </form>


<?php
} } // end body submit    
?>

</article>
<aside id="other">

<?php if (isset($_POST["btnFindUser"])){ ?>
    <h2>Delete Your Account</h2>
    <p>Follow <a href="delete.php">this link</a> to permanently delete your account.</p>

<?php } //ends if button find user is pressed
?>

<?php /**if (isset($_POST["btnDelete"])){

$q2 = "DELETE FROM tblRegister WHERE pmkRegisterId = ? ";// . $registerId;//fldEmail = " . $email;
$data2 = array($registerId);
$dlt = $thisDatabase->update($q2, $data2);

if(debug){
print "<p> registerId = " . $registerId . "</p>";
print "<p> q2 = " . $q2 . "</p>";
print "<p> print_r(data2) = " . print_r($data2) . "</p>"; 
print "<p> print_r(dlt) = " . print_r($dlt) . "</p>"; 
}

}// ends if button delete is pressed
 */

?>
</aside> 
<?php include "footer.php"; ?>
</body>
</html>

