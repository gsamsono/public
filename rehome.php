<?php
include "top.php";

/* ##### Step one
 *
 * create your database object using the appropriate database username
 * CREATE TABLE IF NOT EXISTS `tblRegister` (
  `pmkRegisterId` int(11) NOT NULL AUTO_INCREMENT,
  ........
 * ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
 */

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.

        $debug = false;

        if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
            $debug = true;
        }

        if ($debug) {
            print "<p>DEBUG MODE IS ON</p>";
        }

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
        $yourURL = $domain . $phpSelf;

    if (isset($_POST["btnFindUser"])){

    $email = filter_var($_POST["hdnEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;
    
    $registerId = filter_var($_POST["hdnRegisterId"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $registerId;

$password = htmlentities($_POST["hdnPassword"], ENT_QUOTES, "UTF-8");
$dataRecord[] = $password;

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

}//ends if (isset($_POST["btnFindUser"]))
        
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form
        $petName = "";
        $age = "";
        $file = null;
        $gender = 'Male';
        $description = "";
        $color = "";
        $category = "";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
        $petNameERROR = false;
        $ageERROR = false;
        $imageERROR = false;
        $descriptionERROR = false;
        $colorERROR = false;
        $categoryERROR = false;
        $emailERROR = false;
        $registerIdERROR = false;
$passwordERROR = false;
        
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
        $errorMsg = array();
// array used to hold form values that will be written to a CSV file
//      $dataRecord = array();
$mailed=false; // have we mailed the information to the user?
$messageA = "";
$messageB = "";
$messageC = "";
//
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
        if (isset($_POST["btnSubmit"])) {       //starts body submit

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2a Security
        
            /* if (!securityCheck(true)) {
              $msg = "<p>Sorry you cannot access this page. ";
              $msg.= "Security breach detected and reported</p>";
              die($msg);
              } */
            
        //}
       
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2b Sanitize (clean) data 
// remove any potential JavaScript or html code from users input on the
// form. Note it is best to follow the same order as declared in section 1c.

            $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
            $dataRecord[] = $email;
            $registerId = htmlentities($_POST["txtRegisterId"], ENT_QUOTES, "UTF-8");
            $dataRecord[] = $registerId;
	$password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
            $dataRecord[] = $password;
            
            $petName = htmlentities($_POST["txtPetName"], ENT_QUOTES, "UTF-8");
            $dataRecord[] = $petName;
            $age = htmlentities($_POST["txtAge"], ENT_QUOTES, "UTF-8");
            $dataRecord[] = $age;
            $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");
            $dataRecord[] = $gender;
            $description = htmlentities($_POST["txtDescription"], ENT_QUOTES, "UTF-8");
            $dataRecord[] = $description;
            $color = htmlentities($_POST["lstColor"], ENT_QUOTES, "UTF-8");
            $dataRecord[] = $color;
            $category = htmlentities($_POST["lstCategory"], ENT_QUOTES, "UTF-8");
            $dataRecord[] = $category;
            
            $file = $_FILES['image']['tmp_name'];
            $dataRecord[] = $file;
            
            $image = htmlentities(file_get_contents($_FILES['image']['tmp_name'])); //previously addslashes()
            $dataRecord[] = $image;
            
            $image_name = htmlentities($_FILES['image']['name']); //previously addslashes()
            $dataRecord[] = $image_name;
            
            $email = filter_var($_POST["hdnEmail"], FILTER_SANITIZE_EMAIL);
            $registerId = filter_var($_POST["hdnRegisterId"], ENT_QUOTES, "UTF-8");
	$password = htmlentities($_POST["hdnPassword"], ENT_QUOTES, "UTF-8");

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@Ï
//
// SECTION: 2c Validation
// Validation section. Check each value for possible errors, empty or
// not what we expect. You will need an IF block for each element you will
// check (see above section 1c and 1d). The if blocks should also be in the
// order that the elements appear on your form so that the error messages
// will be in the order they appear. errorMsg will be displayed on the form
// see section 3b. The error flag ($emailERROR) will be used in section 3c.

            if (is_numeric($petName)) {
                $errorMsg[] = "Please enter pet name only";
                $petNameERROR = true;
            } if ($petName == "") {
                $errorMsg[] = "Please enter your pet name";
                $petNameERROR = true;
            } if ($age == "") {
                $errorMsg[] = "Please enter your pets age";
                $ageERROR = true;
            } if (!is_numeric($age)) {
                $errorMsg[] = "Please enter age as a numeric value";
                $ageERROR = true;
            } if ($description == "") {
                $errorMsg[] = "Please enter your pets description";
                $descriptionERROR = true;
            } if ($color == "") {
                $errorMsg[] = "Please select a color";
                $colorERROR = true;
            } if ($category == "") {
                $errorMsg[] = "Please select a category";
                $categoryERROR = true;
            } if (($email != "") && !verifyEmail($email)) {
                $errorMsg[] = "Your email address appears to be incorrect.";
                $emailERROR = true;
            } if($email === "") {
                $errorMsg[] = "Please enter your email address.";
                $emailERROR = true;
            } if($password === "") {
        	$errorMsg[] = "Please enter your password.";
        	$passwordERROR = true;
            }
		if ($file == null) {
                $errorMsg[] = "Please select an image.";
                $imageERROR = true;
            } 
            if ($file != null) {
                $image_size = getimagesize($_FILES['image']['tmp_name']);
                if ($image_size == FALSE) {
                    $errorMsg[] = "That is not an image.";
                    $imageERROR = true;
                }
            }

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2d Process Form - Passed Validation
//
// Process for when the form passes validation (the errorMsg array is empty)
//            
            if (!$errorMsg) {       //starts body process form 
                if ($debug) {
                    print "<p>Form is valid</p>";
                    print "<p> email = " . $email . "</p>";
                    print "<p> registerId = " . $registerId . "</p>";
		print "<p> password = " . $password . "</p>";
                }
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2e Save Data
//
//                
                $primaryKey = "";
                $dataEntered = false;
                try {
                    $thisDatabase->db->beginTransaction();
                    
    //find the users id # from their email
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
    }//ends for each loop
                    
                    $data = array();
                    $query = 'INSERT INTO tblPets SET fnkRegisterId = ?, fldPetName = ?, fldAge = ?, fldCategory = ?, ';
                    $query .= 'fldGender = ?, fldDescription = ?, fldColor = ?, fldImgName = ?, fldImg = ? ';
                    $data[] = $registerId;
                    $data[] = $petName;
                    $data[] = $age;
                    $data[] = $category;
                    $data[] = $gender;
                    $data[] = $description;
                    $data[] = $color;
                    $data[] = $image_name;
                    $data[] = $image;
                    
                    if ($debug) {
                        print "<p>query: " . $query . "</p>";
                        print "<p>print_r(data): " . print_r($data) . "</p>";
                    }
                    $results = $thisDatabase->insert($query, $data);
                    
//************************************************************************************************
                    $primaryKey = $registerId; //$thisDatabase->lastInsert();
                    if ($debug)
                        print "<p>pmk= " . $primaryKey;
                    // all sql statements are done so lets commit to our changes
                    $dataEntered = $thisDatabase->db->commit();
                    $dataEntered = true;
                    if ($debug)
                        print "<p>transaction complete ";
                } catch (PDOException $e) {
                    $thisDatabase->db->rollback();
                    if ($debug)
                        print "Error!: " . $e->getMessage() . "</br>";
                    $errorMsg[] = "There was a problem with accpeting your data please contact us directly.";
                }
                // If the transaction was successful, give success message
                if ($dataEntered) {
                    if ($debug) {
                        print "<p>data entered now prepare keys ";
                    }
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
                print "<p> results = " . $results . "</p>"; 
            }
                    
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2f Create message
            $messageA = '<h1>Thank you for adding your pet to the database!</h1>';
        $message = '<h1>Your pets information:</h1>';

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
        $subject = "Your pet has been added to the database!";

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $messageA . $messageB . $messageC . $message);
        
   } // ends data entered
////@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2f Create message
//
// build a message to display on the screen in section 3a and to mail
// to the person filling out the form (section 2g).
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2g Mail to user
//
// Process for mailing a message which contains the forms data
// the message was built in section 2f.                    
            }   //ends body process form / form is valid
        } //ends body submit / if form was submitted
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
// If the form has been submitted and there are no errors.
            if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
                ?>
                <!-- <form action="<?php print $phpSelf; ?>"
                      method="post"
                      id="frmRegister"> -->
                          <?php
                          print "<h1>Your pet has been added to the database and will find a new home soon!</h1>";
                          
        print "<p>A copy of your pets information has ";
        if (!$mailed) {
            print "NOT ";
        }
        print "been sent to your email address.</p>";
        print $message;
                          
                      } else { //If the form hasn't been submitted or there are errors.
//####################################
//
// SECTION 3b Error Messages
//
// display any error messages before we print out the form
//if there are errors in the input
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
//Display the HTML form. note that the action is to this same page. $phpSelf
//is defined in top.php
//NOTE the line:
//value="<?php print $email; 
// this makes the form sticky by displaying either the initial default value (line 35)
//or the value they typed in (line 84)
//NOTE this line:
//<?php if($emailERROR)print 'class="mistake"'; 
//this prints out a css class so that we can highlight the background etc. to
//make it stand out that a mistake happened here.
?>                  

                    <form action="<?php print $phpSelf; ?>"
                          method="POST" 
                          enctype="multipart/form-data" 
                          id="ReHome">
                        
                        <fieldset class="wrapper">
                            <legend>Rehome Your Pet!</legend>

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
<p>Please register <a href="register.php">here</a> before adding a pet!</p>

<?php 
} //end if (!isset($_POST["btnFindUser"]))
else{  //if btnFindUser was pressed

$email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
$dataRecord[] = $email;
$password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
$dataRecord[] = $password;

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
	<p>Try again <a href="rehome.php">here</a>.</p>
        <p>Or if you are a new user, please register <a href="register.php">here</a> before adding a pet.</p>
        <?php 
    } else {
        if ($debug){
            print "registerId is not empty...";
            print "<p>registerId: " . $registerId . "</p>";
        }
//*************************************************************************************/
?>
                                
                        <fieldset class="search"><legend>Please complete the following form:</legend>
                                    
                        <input type="hidden" id="hdnEmail" name="hdnEmail" value="<?php print $email; ?>">
                        <input type="hidden" id="hdnRegisterId" name="hdnRegisterId" value="<?php print $registerId; ?>">
			<input type="hidden" id="hdnPassword" name="hdnPassword" value="<?php print $password; ?>">
                        
                        <?php 
			print "<p> Your email address: " . $email . "</p>";
			print "<p> Your unique user ID: " . $registerId . "</p>";
                        if($debug) {
				print "<p> Your password: " . $password . "</p>"; 
			} ?>
                                    
                                    <label for = "txtPetName" class = "required">Pet Name
                                        <input type = "text" id = "txtPetName" name = "txtPetName"
                                               value = "<?php print $petName; ?>"
                                               tabindex = "100" maxlength = "45" placeholder = "Enter pet name"
                                               <?php if ($petNameERROR) print 'class="mistake"'; ?>
                                               onfocus="this.select()"
                                               autofocus>
                                    </label>
                                    <!-- ends pet name -->
                                    <!--<legend>age Information</legend>-->
                                    <label for="txtAge" class="required">Age
                                        <input type="text" id="txtAge" name="txtAge"
                                               value="<?php print $age; ?>"
                                               tabindex="200" maxlength="45" placeholder="Enter age"
                                               <?php if ($ageERROR) print 'class="mistake"'; ?>
                                               onfocus="this.select()" 
                                               >
                                    </label>
                                    <!-- ends age -->

                                    <!--<legend>Gender Information</legend>-->
                                    <label for="radGender">
                                        <input type="radio" 
                                               id="radMale" 
                                               name="radGender" 
                                               checked
                                               value="Male">Male

                                        <input type="radio" 
                                               id="radFemale" 
                                               name="radGender" 
                                               value="Female">Female
                                    </label>
                                    <!-- ends gender -->
                                    <!--<legend>Description Information</legend>-->
                                    <label for="txtDescription" class="required">Description
                                        <input type="text" id="txtDescription" name="txtDescription"
                                               value="<?php print $age; ?>"
                                               tabindex="200" maxlength="45" placeholder="Enter pet description like: friendly"
                                               <?php if ($descriptionERROR) print 'class="mistake"'; ?>
                                               onfocus="this.select()" 
                                               >
                                    </label>
                                    <!-- ends description -->
                                    <!--<legend>Color Information</legend>-->
                                    <label for="lstColor">Color
                                        <select id="lstColor"
                                                name="lstColor"
                                                tabindex="300" >
                                            <option value="" selected></option>
                                            <option value="Black">Black</option>
                                            <option value="Blue">Blue</option>
                                            <option value="Brown">Brown</option>
                                            <option value="Green">Green</option>
                                            <option value="Grey">Grey</option>
                                            <option value="Orange">Orange</option>
                                            <option value="Other">Other</option>
                                            <option value="Pink">Pink</option>
                                            <option value="Purple">Purple</option>
						<option value="Red">Red</option>
                                            <option value="White">White</option>
                                            <option value="Yellow">Yellow</option>
                                        </select></label>
                                    <!-- ends color -->
                                    
                                    <label for="lstCategory">Category
                                        <select id="lstCategory"
                                                name="lstCategory"
                                                tabindex="400" >
                                            <option value="" selected></option>
                                            <option value="Amphibian">Amphibian</option>
                                            <option value="Bird">Bird</option>
                                            <option value="Cat">Cat</option>
                                            <option value="Cattle">Cattle</option>
                                            <option value="Crab/Fish">Crab/Fish</option>
                                            <option value="Dog">Dog</option>
                                            <option value="Farm">Farm</option>
                                            <option value="Ferret">Ferret</option>
                                            <option value="Fowl">Fowl</option>
                                            <option value="Goat">Goat</option>
                                            <option value="Guinea Pig">Guinea Pig</option>
                                            <option value="Horse">Horse</option>
                                            <option value="Pig">Pig</option>
                                            <option value="Rabbit">Rabbit</option>
                                            <option value="Reptile">Reptile</option>
                                            <option value="Rodent">Rodent</option>
                                            <option value="Sheep">Sheep</option>
                                            <option value="Other">Other</option>
                                        </select></label>

                                    <!--<legend>Image Information</legend>-->
                                    Upload an Image:
                                    <input type="file" name="image">
                                    <!--  ends image -->
                                    
                                </fieldset> <!-- ends <fieldset class="search"> -->
                                <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" class="button">
                            </fieldset> <!-- ends wrapper Two -->
                        </fieldset> <!-- Ends Wrapper -->
                    </form>

                <?php
} //ends if registerId IS NOT empty
}  } //end body submit
                ?>

</article>
<?php include "footer.php"; ?>
</body>
</html>
