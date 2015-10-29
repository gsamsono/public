<?php
include "top.php";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = false;//true;

if (isset($_POST["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// SECTION: 1b Security
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;

/**if (isset($_POST["btnSubmit"]){
	$registerId = filter_var($_POST["hdnRegisterId"], ENT_QUOTES, "UTF-8");
	$dataRecord[] = $registerId;
}*/

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// SECTION: 1c form variables
// Initialize variables one for each form element
// in the order they appear on the form
$petName = "";
$age = "";
//$color = "";
$dateAdded = "";
$registerId = ""; //the users ID
$petDesc = "";
$petId = "";
$gender = "";


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// SECTION: 1d form error flags
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$petNameERROR = false;
$ageERROR = false;
//$colorERROR = false;
$dateAddedERROR = false;
$registerIdERROR = false; //the users ID
$petDescERROR = false;
$petIdERROR = false;


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// SECTION: 1e misc variables
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// array used to hold form values that will be written to a CSV file
$dataRecord = array();

$mailed=false; // have we mailed the information to the user?
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
// SECTION: 2 Process for when the form is submitted
if (isset($_POST["btnSubmit"])) {

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    // SECTION: 2a Security
    if (!securityCheck(true)) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    // SECTION: 2b Sanitize (clean) data 
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order as declared in section 1c.

    $petName = htmlentities($_POST["txtPetName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $petName;
            
    $age = htmlentities($_POST["txtAge"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $age;
            
    $dateAdded = htmlentities($_POST["txtDateAdded"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $dateAdded;
            
    $registerId = htmlentities($_POST["txtRegisterId"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $registerId;
            
    $category = htmlentities($_POST["lstCategory"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $category;
    
    $color = htmlentities($_POST["lstColor"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $color;

$petDesc = htmlentities($_POST["txtPetDesc"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $petDesc;
    
$petId = htmlentities($_POST["txtPetId"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $petId;

    $gender = htmlentities($_POST["chkGender"], ENT_QUOTES, "UTF-8");

    /** if(isset($_POST["chkZSection"])) {
        $chkZSection = true;
    }else{
        $chkZSection = false;
    } */

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    // SECTION: 2d Process Form - Passed Validation
    // Process for when the form passes validation (the errorMsg array is empty)
    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	// SECTION: 2e prepare query
//R.pmkRegisterId,

$query = "SELECT P.fldPetName as 'Pet Name', P.fldCategory as 'Category', ";
$query .= "P.fldAge as 'Pet Age', P.fldGender as 'Gender', P.fldColor as 'Color', ";
$query .= "P.fldDateAdded as 'Date Added', "; //P.fldImgName as 'Image Name',
$query .= "P.fldDescription as 'Description', P.pmkPetId as 'Pet Id', ";
$query .= "P.fnkRegisterId as 'Registered By User' "; // P.fldImg,
$query .= "FROM tblPets P, tblRegister R ";
$query .= "WHERE (P.fnkRegisterId = R.pmkRegisterId) ";//AND (P.fldGender LIKE ? ) ";

$data = array(); //create array

//for genders
if (isset($_POST["chkFemale"]) && !isset($_POST["chkMale"])) {
    $query .= "AND (P.fldGender LIKE 'Female' ) ";
} if (isset($_POST["chkMale"]) && !isset($_POST["chkFemale"])) {
    $query .= "AND (P.fldGender LIKE 'Male' ) ";
} 

    if ($petName != "") {
    //for when the user inputs a pet name
    $query .= "AND (P.fldPetName LIKE ? ) ";
    $data[] = $petName; 

        } if ($age != "") {
        //for when the user inputs an age
$query .= "AND (P.fldAge LIKE ? ) ";
$data[] = $age;
        
        } if ($dateAdded != "") {
        //for when the user inputs a date added
$query .= "AND (P.fldDateAdded LIKE ? ) ";   
$data[] = $dateAdded;
        
        } if ($registerId != "") {
        //for when the user inputs a user's ID who added a pet
$query .= "AND (P.fnkRegisterId LIKE ? ) "; 
$data[] = $registerId;
        
        } if ($color != "") {
        //for when the user inputs a pet category/type
$query .= "AND (P.fldColor LIKE ? ) ";  
$data[] = $color;

        } if ($category != "") {
        //for when the user inputs a pet category/type
$query .= "AND (P.fldCategory LIKE ? ) ";  
$data[] = $category;

        } if ($petDesc != "") {
        //for when the user inputs a description
$query .= "AND (P.fldDescription LIKE ? ) ";  
$data[] = $petDesc;

        } if ($petId != "") {
        //for when the user inputs the pets id
$query .= "AND (P.pmkPetId LIKE ? ) "; 
$data[] = $petId;
        }
//order the results alphabetically
$query .= "ORDER BY fldPetName ASC ";

    // execute query using a  prepared statement
    $results = $thisDatabase->select($query, $data);

    if ($debug) {
                print "<p>query: " . $query . "</p>";
                print "<p>data: " . $data . "</p>";
                print "<p>print_r(data): " . print_r($data) . "</p>";
                print "<p>results: " . $results . "</p>"; 
                print "<p>print_r(results): " . print_r($results) . "</p>"; 
            }
    
     /**  prepare output and loop through array  */
    $numberRecords = count($results);
    print "<h1>Total Results: " . $numberRecords . "</h1>";
    print "\r\n<table>";
    $firstTime = true;

    /* since it is associative array display the field names */
    foreach ($results as $row) {
	//$imgName = $row["fldImgName"];
	//print "<p>imgName: " . $imgName . "</p>";
        /**$registerId = $row["fnkRegisterId"];
        if ($debug){
            print "<p>registerId: " . $registerId . "</p>";
            print '<p>row[fnkRegisterId]: ' . $row["fnkRegisterId"] . '</p>';
        }*/
            
        if ($firstTime) {
            print "\r\n<thead><tr>\r\n";
            $keys = array_keys($row);
            foreach ($keys as $key) {
                if (!is_int($key)) {
                    print "<th>" . $key . "</th>\r\n";
                }
            }
            print "<th>Contact Owner?</th>";
            //print "</tr>\r\n";
	//print "<th>Image</th>";
          //  print "</tr>\r\n";

            $firstTime = false;
        }
        
        /* display the data, the array is both associative and index so we are
         *  skipping the index otherwise records are doubled up */
        print "\r\n<tr>\r\n";
        foreach ($row as $field => $value) {
            if (!is_int($field)) {
                print "<td>" . $value . "</td>\r\n";
	/**if ($row[5]){
		$imgName = $value;
echo '<td><img src="/petpix/' . $imgName . '" /></td>';
	}*/
//$imgName = $row["fldImgName"];
//print "<p>imgName: " . $imgName . "</p>";
            }
//$imgName = $row["fldImgName"];
//print "<p>imgName: " . $imgName . "</p>";
        
//$dateSubmitted = $results[0]["fldDateJoined"]; //************************************************************/
/**if ($row["Image Name"] && $row[5]){
		$imgName = $field;
echo '<td><img src="/petpix/' . $imgName . '" /></td>';
	} */

	}
	print '<td><a href="contact.php?id=' . $row["Registered By User"] . '">[Click to Contact]</a></td>'; //'-' . $row[8] . 
        //print "\r\n";         $row[8]             fnkRegisterId as 'Registered By User'
        //print '<p><img src="petpix/' . $row["fldImgName"] . '" alt="' . $row["fldPetName"] . '-image" /></p>';
	//print "<p>imgName: " . $row["fldImgName"] . "</p>";
	//echo '<td><img src="/petpix/' . $row[5] . '" /></td>';
        print "</tr>\r\n";

    } //ends foreach ($results as $row)
    print "</table>\r\n";
    
        
    } // end form is valid
} // ends if form was submitted.  
//#############################################################################
// SECTION 3 Display Form
?>

<article id="main">
    <?php
    //####################################
    // SECTION 3a.
    // If its the first time coming to the form or there are errors we are going
    // to display the form.
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        /**print "<h1>Your Request has ";
        if (!$mailed) {
            print "not "; }
        print "been processed</h1>";
        print "<p>A copy of this message has ";
        if (!$mailed) {
            print "not "; }
        print "been sent</p>";
        print "<p>To: " . $email . "</p>";
        print "<p>Mail Message:</p>";
        print $message; */
    } else {
        //####################################
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
          make it stand out that a mistake happened here.
         */
        ?>

        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">

            <fieldset class="wrapper">
                <legend>Search for a potential pet:</legend>
                <fieldset class="wrapperTwo">
                    <legend>Enter as many or as few fields as you would like</legend>
                    <fieldset class="search">
                        
			<label for="txtPetName">Pet Name
                            <input type="text" id="txtPetName" name="txtPetName"
                                   value=""
                                   tabindex="100" maxlength="100" placeholder="Enter a name like: Ash"
                                       onfocus="this.select()"
                                   autofocus></label>

                        <label for="txtAge">Age
                            <input type="text" id="txtAge" name="txtAge"
                                   value=""
                                   tabindex="200" maxlength="100" placeholder="Enter an age like: 2"
                                       onfocus="this.select()"
                                   autofocus></label>

 <?php 
// Step Two: code can be in initialize variables or where step four needs to be
$query2  = "SELECT DISTINCT fldColor ";
$query2 .= "FROM tblPets ";
$query2 .= "ORDER BY fldColor";


// Step Three: code can be in initialize variables or where step four needs to be
// $buildings is an associative array
$colors = $thisDatabase->select($query2);

$output = array();
$output[] = '<label for="lstColor">Color ';
$output[] = '<select id="lstColor" ';
$output[] = '        name="lstColor"';
$output[] = '        tabindex="250" >';
$output[] = '        <option selected value="">Any</option>';

foreach ($colors as $row) {

    $output[] = '<option ';
    if ($color == $row["fldColor"])
        $output[] = ' selected ';

    $output[] = 'value="' . $row["fldColor"] . '">' . $row["fldColor"];

    $output[] = '</option>';
}

$output[] = '</select></label>';

print join("\n", $output);  // this prints each line as a separate  line in html
?>

                        <label for="txtRegisterId">Added By User ID
                            <input type="text" id="txtRegisterId" name="txtRegisterId"
                                   value=""
                                   tabindex="400" maxlength="100" placeholder="Enter users ID that added the pet"
                                       onfocus="this.select()"
                                   autofocus></label>

			<label for="txtPetId">Pet ID
                            <input type="text" id="txtPetId" name="txtPetId"
                                   value=""
                                   tabindex="420" maxlength="100" placeholder="Enter pets ID number"
                                       onfocus="this.select()"
                                   autofocus></label>
                        
 <?php 
// Step Two: code can be in initialize variables or where step four needs to be
$query2  = "SELECT DISTINCT fldCategory ";
$query2 .= "FROM tblPets ";
$query2 .= "ORDER BY fldCategory";


// Step Three: code can be in initialize variables or where step four needs to be
// $buildings is an associative array
$categorys = $thisDatabase->select($query2);

$output = array();
$output[] = '<label for="lstCategory">Category ';
$output[] = '<select id="lstCategory" ';
$output[] = '        name="lstCategory"';
$output[] = '        tabindex="600" >';
$output[] = '        <option selected value="">Any</option>';

foreach ($categorys as $row) {

    $output[] = '<option ';
    if ($category == $row["fldCategory"])
        $output[] = ' selected ';

    $output[] = 'value="' . $row["fldCategory"] . '">' . $row["fldCategory"];

    $output[] = '</option>';
}

$output[] = '</select></label>';

print join("\n", $output);  // this prints each line as a separate  line in html
 ?>  

			<label for="txtPetDesc">Description of Pet
                            <input type="text" id="txtPetDesc" name="txtPetDesc"
                                   value=""
                                   tabindex="650" maxlength="100" placeholder="Enter a pets description"
                                       onfocus="this.select()"
                                   autofocus></label>


<?php
// Step Two: code can be in initialize variables or where step four needs to be
$query  = "SELECT DISTINCT fldGender ";
$query .= "FROM tblPets ";

// Step Three: code can be in initialize variables or where step four needs to be
// an associative array
$gender = $thisDatabase->select($query);

// Step Four: prepare output two methods, only do one of them
//  Here is how to code it 

$output = array();
$output[] = '<fieldset class="checkbox">';
$output[] = '<legend>Gender:</legend>';


foreach ($gender as $row) {

    $output[] = '<label for="chk' . str_replace(" ", "-", $row["fldGender"]) . '"><input type="checkbox" ';
    $output[] = ' id="chk' . str_replace(" ", "-", $row["fldGender"]) .  '" ';
    $output[] = ' name="chk' . str_replace(" ", "-", $row["fldGender"]) .  '" ';             
    $output[] = 'value="' . $row["fldGender"] . '">' . $row["fldGender"] . ' ';
    $output[] = '</label>';
}

$output[] = '</fieldset>';

print join("\n", $output);  // this prints each line as a separate  line in html
?>
                        

                    </fieldset> <!-- ends search -->   
                </fieldset> <!-- ends wrapper Two -->
                
                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Search" tabindex="900" class="button">
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