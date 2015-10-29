

<?php
include "top.php";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
//
/** create your database object using the appropriate database username  
require_once('../bin/myDatabase.php');

$dbFirstName = get_current_user() . '_reader';
$whichPass = "r"; //flag for which one to use.
$dbName = strtoupper(get_current_user()) . '_crud';

$thisDatabase = new myDatabase($dbFirstName, $whichPass, $dbName); */

// SECTION: 1a.
// variables for the classroom purposes to help find errors.

//$debug = true;
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


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form
/**$firstName = "grace";
$lastName = "samsonow";
$email = "gsamsono@uvm.edu";
$phone = "8021231234";
$address = "12 jahsk";
$city = "burl";
$state = "vt";
$zip = "05401";
$bio = "kjhaskdga";
$password = "test"; */

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
if (isset($_POST["btnSubmit"])) {

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2a Security
    // 
    if (!securityCheck(true)) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2b Sanitize (clean) data 
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order as declared in section 1c.

    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;

    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
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

    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to be incorrect.";
        $firstNameERROR = true;
    }

    if ($lastName == "") {
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your last name appears to be incorrect.";
        $lastNameERROR = true;
    }

    if ($email == "") {
        $errorMsg[] = "Please enter your email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    }

if ($phone == "") {
        $errorMsg[] = "Please enter your phone number";
        $phoneERROR = true;
    } elseif (!verifyNumeric($phone)) {
        $errorMsg[] = "Your phone number appears to be incorrect.";
        $phoneERROR = true;
    }
if ($address == "") {
        $errorMsg[] = "Please enter your street address";
        $addressERROR = true;
    } elseif (!verifyAlphaNum($address)) {
        $errorMsg[] = "Your address appears to be incorrect.";
        $addressERROR = true;
    }
if ($city == "") {
        $errorMsg[] = "Please enter your city";
        $cityERROR = true;
    } elseif (!verifyAlphaNum($city)) {
        $errorMsg[] = "Your city appears to be incorrect.";
        $cityERROR = true;
    }
if ($state == "") {
        $errorMsg[] = "Please enter your state";
        $stateERROR = true;
    } elseif (!verifyAlphaNum($state)) {
        $errorMsg[] = "Your state appears to be incorrect.";
        $stateERROR = true;
    }
if ($zip == "") {
        $errorMsg[] = "Please enter your zip code";
        $zipERROR = true;
    } elseif (!verifyNumeric($zip)) {
        $errorMsg[] = "Your zip code appears to be incorrect.";
        $zipERROR = true;
    }
//bio can be empty
if ($password == "") {
        $errorMsg[] = "Please enter your password";
        $passwordERROR = true;
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
            $query = 'INSERT INTO tblRegister SET fldEmail = ?, fldFirstName = ?, fldLastName = ?, ';
            $query .= 'fldPhone = ?, fldAddress = ?, fldCity = ?, fldState = ?, fldZip = ?, ';
	    $query .= 'fldBio = ?, fldPassword = ? ';
            $data = array($email, $firstName, $lastName, $phone, $address, $city, $state, $zip, $bio, $password);
            
            if ($debug) {
                print "<p>query: " . $query . "</p>";
                print"<p>print_r(data): " . print_r($data) . "</p>";
            }
            $results = $thisDatabase->insert($query, $data);

            $primaryKey = $thisDatabase->lastInsert();
            if ($debug)
                print "<p>pmk: " . $primaryKey . "</p>";

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

            if ($debug)
                print "<p>key 1: " . $key1;
            if ($debug)
                print "<p>key 2: " . $key2;

 //#################################################################
            //
            //Put forms information into a variable to print on the screen
            //

            $messageA = '<h2>Thank you for registering on our website!</h2>';
            $messageA .= "<p>Dear " . $firstName . " " . $lastName . ", </p>";
            $messageB = "<p>Please follow this link to confirm your registration: ";
            $messageB .= '<a href="' . $domain . $path_parts["dirname"] . '/confirmation.php?q=' . $key1 . '&amp;w=' . 
		$key2 . '">Confirm Registration</a></p>';
            $messageB .= "<p>Or copy and paste this url into your web browser: </p>";
            $messageB .= "<p>" . $domain . $path_parts["dirname"] . '/confirmation.php?q=' . $key1 . '&amp;w=' . $key2 . "</p>";

            $messageC = '<h1>Your account information:</h1>';
        foreach ($_POST as $key => $value) {
            if($key != "btnSubmit"){
                $messageC .= "<p>";
                $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));
                foreach ($camelCase as $one) {
                    $messageC .= $one . " ";
                }
                $messageC .= ":  " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
            } //ends if($key != "btnSubmit")
        } //ends foreach loop
            
            //$messageC .= "<p><b>Email Address: </b><i>   " . $email . "</i></p>";

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2f Create message
        //
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g).

        $message = '<h1>Your account information:</h1>';

        foreach ($_POST as $key => $value) {
            
            if($key != "btnSubmit"){
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
        $subject = "Registration Confirmation";//$todaysDate;

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $messageA . $messageB . $messageC);
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
    //
    // SECTION 3a.
    //
    // 
    // 
    // 
    // If its the first time coming to the form or there are errors we are going
    // to display the form.
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print "<h1>Your registration request has ";

        if (!$mailed) {
            print "NOT ";
        }

        print "been processed. </h1>";

        print "<p>A copy of your account information has ";
        if (!$mailed) {
            print "NOT ";
        }
        print "been sent to: " . $email;
        if ($mailed){
            print " along with a confirmation link. </p>";
        }

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
                <legend>Sign Up Today!</legend>

                <fieldset class="wrapperTwo">
                    <legend>Please complete the following form: </legend>

                    <fieldset class="search">
                        <!-- <legend>Your Information</legend> -->
                        <label for="txtFirstName" class="required">First Name
                            <input type="text" id="txtFirstName" name="txtFirstName"
                                   value="<?php print $firstName; ?>"
                                   tabindex="100" maxlength="100" placeholder="Your first name"
                                   <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>
                        
			<label for="txtLastName" class="required">Last Name
                            <input type="text" id="txtLastName" name="txtLastName"
                                   value="<?php print $lastName; ?>"
                                   tabindex="110" maxlength="100" placeholder="Your last name"
                                   <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>

                        <label for="txtEmail" class="required">Email
                            <input type="text" id="txtEmail" name="txtEmail"
                                   value="<?php print $email; ?>"
                                   tabindex="120" maxlength="45" placeholder="Example: person@yahoo.com"
                                   <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>

			<label for="txtPhone" class="required">Phone
                            <input type="text" id="txtPhone" name="txtPhone"
                                   value="<?php print $phone; ?>"
                                   tabindex="130" maxlength="45" placeholder="Example: 8021231234"
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
                                   tabindex="160" maxlength="45" placeholder="Example: VT"
                                   <?php if ($stateERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
				   autofocus>
                        </label>

			<label for="txtZip" class="required">Zip Code
                            <input type="text" id="txtZip" name="txtZip"
                                   value="<?php print $zip; ?>"
                                   tabindex="170" maxlength="45" placeholder="Example: 05401"
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
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="900" class="button">
                </fieldset> <!-- ends buttons -->
                
            </fieldset> <!-- Ends Wrapper -->
        </form>

    <?php
    } // end body submit
    ?>

</article>

<?php include "footer.php"; ?>

</body>
</html>

