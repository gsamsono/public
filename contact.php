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

if (isset($_GET["id"])) {
    $registerId = htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $registerId;
    if ($debug) {
        print "<p>user trying to contact: regID: " . $registerId . "</p>";
    }
    
    $query2 = 'SELECT fldEmail, pmkRegisterId FROM tblRegister WHERE pmkRegisterId = ? ';
    $results2 = $thisDatabase->select($query2, array($registerId));
    $email2 = $results2[0]["fldEmail"];
    if ($debug) {
        print "<p>user trying to contact: regID: " . $registerId . "</p>";
    }
}

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form

//need to get these from the database once the user enters their email address
//$registerId = "4";
//$email = "";
//$password = "password";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.

$emailERROR = false;
$passwordERROR = false;
$registerIdERROR = false;
$registerIdCurrERROR = false;

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
if (isset($_POST["btnContact"])) {

if($debug){
    print "<p> user id trying to contact = " . $registerId . "</p>"; 
    print "<p> email = " . $email . "</p>";
    print "<p> password = " . $password . "</p>";
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

$registerId = htmlentities($_POST["hdnRegisterId"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $registerId;
$email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;
$password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $password;

if ($debug){
    print "<p>user trying to contact: regID: " . $registerId . "</p>";
}

    if (($email != "") && !verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    }
    if($email === "") {
        $errorMsg[] = "Please enter your email address.";
        $emailERROR = true;
    }

    if($password === "") {
        $errorMsg[] = "Please enter your password.";
        $passwordERROR = true;
    }

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

            
            //*************************************************************************************/
    //find the current users id # and password from their email
    $q = "SELECT fldEmail, fldPassword, pmkRegisterId FROM tblRegister WHERE (fldEmail LIKE ?) AND (fldPassword LIKE ?) ";
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
        $registerIdCurr = $row["pmkRegisterId"];
	$password = $row["fldPassword"];
        $email = $row["fldEmail"];
    }//ends for each loop
    
    if ($registerIdCurr == ""){ //so if registerId IS EMPTY (email doesnt exist in db)
        if($debug) {
            print "registerId is empty...";
        }
        print "<p>Your email address and password combination were not found.";
        $registerIdCurrERROR = true;
        $relink = "contact.php?id=" . $registerId;
        ?>
	<p>Try again <a href="<?php print $relink; ?>">here</a>.</p>
        <?php 
    } else {
        if ($debug){
            print "registerIdCurr is not empty...";
            print "<p>registerIdCurr: " . $registerIdCurr . "</p>";
        }
//*************************************************************************************/

    //}
            
            
//get the email of the user trying to contact from their user id
$q1 = "SELECT fldEmail, pmkRegisterId FROM tblRegister ";
$q1 .= "WHERE pmkRegisterId LIKE ? ";
$data = array($registerId);

if ($debug) {
    print "<p>q1: " . $q1 . "</p>";
    print "<p>print_r(data): " . print_r($data) . "</p>";
    }
$results = $thisDatabase->select($q1, $data);
        
foreach ($results as $row) {
	$email2 = $row["fldEmail"]; //the user to contact
}
if ($debug){
    print "<p>email2: " . $email2 . "</p>";
}

            $primaryKey = $registerId;
            if ($debug){
                print "<p>fnk(registerId) = " . $primaryKey . "</p>";
                print "<p> results = " . $results . "</p>"; 
                print "<p> print_r(results) = " . print_r($results) . "</p>"; 
            }
            
// all sql statements are done so lets commit to our changes
            $dataEntered = $thisDatabase->db->commit();
            $dataEntered = true;
            if ($debug)
                print "<p>transaction complete ";
    }//ends else (if(!$registerIdCurr == ""))
        } catch (PDOException $e) {
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
//Put forms information into a variable to print on the screen

            $messageA = '<h1>A user has expressed interest in your pet!</h1>';
            $message =  "<p>This message is to notify you that someone is interested ";
            $message .= "in adopting a pet that you added to our database. </p>";
            $message .= "<p>Please contact them directly at this email address: " . $email . "</p>";
            $message .= "<p>Thank you for using our website! </p>";

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2g Mail to user
        //
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $email2; // the user to contact
        $cc = "";
        $bcc = "";
        $from = "Pet Search Website <noreply@petsearch.com>";

        // subject of mail should make sense to your form
        $todaysDate = strftime("%x");
        $subject = "Someone has expressed interest in your pet!";

        if ($debug){
            print "<p>registerIdCurr: " . $registerIdCurr . "</p>";
        }
        if (!$registerIdCurr == ""){
            $mailed = sendMail($to, $cc, $bcc, $from, $subject, $messageA . $messageB . $messageC . $message);
          }
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
    if (isset($_POST["btnContact"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        /**print "<h1>The user has ";
        if (!$mailed) {
            print "NOT ";
        }
        print "been notified. </h1>"; */
        if ($mailed) {
            print "<h1>The user has been notified. </h1>";
            print "<p>They should respond to your inquiry shortly.</p>";
            print "<p>Thank you for using our website for your pet adoption needs! </p>";
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
            
            //print "<p>Your email address and password combination were not found.";
            $relink = "contact.php?id=" . $registerId;
            ?>
            <p>Try again <a href="<?php print $relink; ?>">here</a>.</p>
            <?php 
        }
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
                <!-- <legend>Contact a User</legend> -->

<?php if (!isset($_POST["btnContact"])) { ?>
                <legend>Contact a User</legend>
                <fieldset class="wrapperTwo">
                    <legend>Please enter your email address and password: </legend>

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
                                   onfocus="this.select()"
                                   autofocus>
			</label><!-- ends password -->
                            
			<p>User you are trying to contact: <?php print $registerId; ?></p>

                        <input type="hidden" id="hdnEmail" name="hdnEmail" value="<?php print $email; ?>">
                        <input type="hidden" id="hdnRegisterId" name="hdnRegisterId" value="<?php print $registerId; ?>">
			<input type="hidden" id="hdnPassword" name="hdnPassword" value="<?php print $password; ?>">
                
                <fieldset class="buttons">
                    <legend>Click this button to send an automatically generated email to this user.</legend>
                    <input type="submit" id="btnContact" name="btnContact" value="Contact" tabindex="900" class="button">
                </fieldset> <!-- ends buttons -->
                </fieldset> <!-- ends wrapper Two -->
<p>New User?</p>
<p>Please register <a href="register.php">here</a> before contacting another user.</p>
                
        </fieldset> <!-- Ends Wrapper -->
        </form>
   
<?php 
} //end if (!isset($_POST["btnContact"]))

?>

</article>
<aside id="other">
</aside> 

<?php include "footer.php"; ?>

</body>
</html>
