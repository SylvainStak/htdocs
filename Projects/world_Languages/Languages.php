<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="Languages.css">
<title>Language Percentages by Country</title>

<!-- CONNECTION TO THE DATABASE -->
<?php
    //Variables to pass as parameters
    $dbServername = "localhost";
    $dbUsername = "root";
    $dbPassword= "";
    $dbName = "world";    

    //Actual connection
    $conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName) or die("Unable to connect.");    

    //This is for keeping the last value selected on country
    $last_country = '';

    if(isset($_GET['countries'])){
        $last_country = $_GET['countries'];
    }
?>

<a href="../../">
    <img id="backpage" src="backarrow.png" alt="backarrow">
</a>



<!-- FORM TO GET THE DATA -->
<form action="" method="get"> 
    <h1>Language Percentage by Country</h1>
    <table>
        <tr>
            <td>
                <select name="countries">
                    <?php
                        echo '<option> -- Select country -- </option>';

                        //Gets countries to show            
                        $sql = "SELECT `Name`,
                                        `Code`
                                FROM `country`
                                ORDER BY `Name`";                    

                        $result = $conn -> query($sql);   

                        //Creates an option for each country in the database
                        //keeping the previous value selected
                        while($row = mysqli_fetch_assoc($result)){
                            if($row['Code'] == $last_country){
                                echo '<option value="' . $row['Code'] . '" selected>' . $row['Name'] . '</option>';
                            }
                            else{
                                echo '<option value="' . $row['Code'] . '">' . $row['Name'] . '</option>';
                            }                
                        }
                    ?>
                </select>
            </td>

            <td><input type="submit" value="SELECT"/></td>
        </tr>    
    <table>
</form>

<!-- RESULTS -->
<?php
    //checks if the user has selected a value
    if(isset($_GET['countries']) && $_GET['countries'] != '-- Select country --'){

        $c_code = $_GET['countries'];

        $sql = "SELECT `Language`,
                       `Percentage`
                FROM `countrylanguage`
                WHERE `CountryCode` = '$c_code'";

        $result = $conn -> query($sql);        

        //If there is any results for the country selected, show data
        if($result->num_rows > 0){
            echo '<table cellspacing="0" id="results">';
            echo '<tr><th colspan="2">COUNTRY: <em>' . $c_code . '</em></th></tr>';
            echo '<tr><th>Language</th><th>Percentage</th></tr>';        

            while($row = mysqli_fetch_assoc($result)){
                echo '<tr><td>' . $row['Language'] . '</td><td>' . $row['Percentage'] . ' <span id="perc">%</span></td></tr>';
            }

            echo '</table>';
        }
        else{
        //No data for country
            echo '<div id="nodata">';
            echo '<h1>No data for the selected country</h1>';
            echo '<h5><em>Country Code: ' . $c_code . '</em></h5>';
            echo '</div>';
        }        
    }
?>