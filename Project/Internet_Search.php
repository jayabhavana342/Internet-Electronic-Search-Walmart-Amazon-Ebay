<html>
<head>
    <title>Project</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
          integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>
</head>
<body>
<h1>This is a Heading</h1>
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>Product</th>
        <th>Price</th>
        <th>Store</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $servername = "mysql.cs.txstate.edu";
    $username = "j_k201";
    $password = "texas@15091994";
    $dbname = "j_k201";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //	echo "Connected successfully";
    // echo "<br />";
    // Connection ends

    //Get value from html
    $searchItem = $_GET["search-by-string"];

    echo "<br />";
    $walmartSearch = str_replace(" ", "%20", $searchItem);
    //	echo $walmartSearch;

    //Declare curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://www.walmart.com/search/?query=$walmartSearch&cat_id=3944");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    curl_close($curl);

    //Create a DOM parser object
    $walmartProducts = array();
    $walmartLinks = array();
    $walmartPrice = array();
    $walmartID = array();
    $StoreName = "Walmart";
    $arr = array();
    $dom = new DOMDocument();

    # The @ before the method call suppresses any warnings that
    # loadHTML might throw because of invalid HTML in the page.
    @$dom->loadHTML($result);


    # Iterate over all the <a> tags
    $divElements = $dom->getElementsByTagName('div');
    $i = -1;
    foreach ($divElements as $divElement) {
        //echo $divElement->nodeValue;
        if ($divElement->hasAttribute('data-item-id')) {
            $i++;
            $walmartID[] = $divElement->getAttribute('data-item-id');
            $walmartPrice[$i] = 0;
            foreach ($divElement->getElementsByTagName('a') as $link) {
                if ($link->getAttribute('class') == "js-product-title") {
                    $walmartProducts[] = $link->nodeValue;
                    $walmartLinks[] = $link->getAttribute('href');
                }
            }
            foreach ($divElement->getElementsByTagName('span') as $link) {
                if ($link->getAttribute('class') == "price price-display"
                    || $link->getAttribute('class') == "price price-display price-not-available") {
                    $originalPrice = $link->nodeValue;
                    $Price = str_replace("$", "", $originalPrice);
                    $Price = str_replace(",", "", $Price);
                    $Price = floatval($Price);
                    $walmartPrice[$i] = $Price;
                }
            }
        }
    }

    // echo "<br />";
    // echo count($walmartLinks);
    // echo "<br />";
    // echo count($walmartProducts);
    // echo "<br />";
    // echo count($walmartID);
    // echo "<br />";
    // echo count($walmartPrice);
    // echo "<br />";

    for ($i = 0; $i < count($walmartLinks); $i++) {
        $walmartLinks[$i] = "http://www.walmart.com" . $walmartLinks[$i];

        // $walmartProducts[$i] = mysql_real_escape_string($walmartProducts[$i]);

        $sql = "INSERT IGNORE INTO Search_Info (searchKey, url,ProductName,ProductID,ProductPrice,StoreName) VALUES ('$searchItem','$walmartLinks[$i]','$walmartProducts[$i]','$walmartID[$i]','$walmartPrice[$i]','Walmart')";
        if ($conn->query($sql) === TRUE) {
            //  echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }


    //AMAZON
    $amazonSearch = str_replace(" ", "+", $searchItem);
    //	echo $walmartSearch;

    // Declare curl
    $amazonCurl = curl_init();
    curl_setopt($amazonCurl, CURLOPT_URL, "http://www.amazon.com/s/ref=sr_nr_p_n_condition-type_0?fst=as%3Aoff&rh=n%3A172282%2Ck%3Atv%2Cp_n_condition-type%3A2224371011&keywords=$amazonSearch");
    curl_setopt($amazonCurl, CURLOPT_RETURNTRANSFER, 1);

    $amazonResult = curl_exec($amazonCurl);
    curl_close($amazonCurl);

    //Create a DOM parser object
    $amazonProducts = array();
    $amazonLinks = array();
    $amazonPrice = array();
    $amazonID = array();
    $amazonName = "Amazon";
    $arr = array();
    $amaz_dom = new DOMDocument();

    # The @ before the method call suppresses any warnings that
    # loadHTML might throw because of invalid HTML in the page.
    @$amaz_dom->loadHTML($amazonResult);

    # Iterate over all the <a> tags
    $divElements = $amaz_dom->getElementsByTagName('li');
    $i = -1;
    foreach ($divElements as $divElement) {
        //echo $divElement->nodeValue;
        if ($divElement->hasAttribute('data-asin')) {
            $i++;
            // echo $i;
            $amazonID[] = $divElement->getAttribute('data-asin');
            $amazonPrice[$i] = 0;
            //	echo $divElement->getAttribute('data-asin');
            // echo "<br>";
            foreach ($divElement->getElementsByTagName('a') as $link) {
                if ($link->getAttribute('class') == "a-link-normal s-access-detail-page  a-text-normal") {
                    $amazonProducts[] = $link->getAttribute('title');
                    // echo $link->getAttribute('title');
                    // echo "<br>";
                    $amazonLinks[] = $link->getAttribute('href');
                    // echo $link->getAttribute('href');
                    // echo "<br>";
                }
            }

            foreach ($divElement->getElementsByTagName('span') as $link) {
                if ($link->getAttribute('class') == "a-size-base a-color-price s-price a-text-bold") {
                    $originalPrice = $link->nodeValue;
                    // echo $originalPrice;
                    // echo "<br>";

                    $Price = str_replace("$", "", $originalPrice);
                    $Price = str_replace(",", "", $Price);
                    $Price = floatval($Price);
                    // echo $Price;
                    // echo "<br>";
                    // echo "<br>";
                    $amazonPrice[$i] = $Price;
                } else {

                }

            }

        }


    }


    // echo "<br />";
    // echo count($amazonLinks);
    // echo "<br />";
    // echo count($amazonProducts);
    // echo "<br />";
    // echo count($amazonID);
    // echo "<br />";
    // echo count($amazonPrice);
    // echo "<br />";

    for ($i = 0; $i < count($amazonLinks); $i++) {

        // $amazonProducts[$i] = mysql_real_escape_string($amazonProducts[$i]);

        $sql = "INSERT IGNORE INTO Search_Info (searchKey, url,ProductName,ProductID,ProductPrice,StoreName) VALUES ('$searchItem','$amazonLinks[$i]', '$amazonProducts[$i]','$amazonID[$i]','$amazonPrice[$i]','Amazon')";


        if ($conn->query($sql) === TRUE) {
            //  echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // End AMAZON


    // Try

    $ebaySearch = str_replace(" ", "+", $searchItem);


    // //Declare curl
    $ebayCurl = curl_init();
    curl_setopt($ebayCurl, CURLOPT_URL, "http://www.ebay.com/sch/i.html?_from=R40&_trksid=p2051541.m570.l1313.TR10.TRC0.A0.H0.X$ebaySearch.TRS0&_nkw=$ebaySearch");
    curl_setopt($ebayCurl, CURLOPT_RETURNTRANSFER, 1);

    $ebayResult = curl_exec($ebayCurl);
    curl_close($ebayCurl);

    // //Create a DOM parser object
    $ebayProducts = array();
    $ebayLinks = array();
    $ebayPrice = array();
    $ebayID = array();
    $ebayName = "Ebay";
    // $arr = array();
    $ebay_dom = new DOMDocument();

    // # The @ before the method call suppresses any warnings that
    // # loadHTML might throw because of invalid HTML in the page.
    @$ebay_dom->loadHTML($ebayResult);


    // # Iterate over all the <a> tags

    //
    $divElements = $ebay_dom->getElementsByTagName('li');
    $i = -1;

    foreach ($divElements as $divElement) {
        // //echo $divElement->nodeValue;
        if ($divElement->hasAttribute('id')) {
            $id = $divElement->getAttribute('id');
            if (strpos($id, 'item') !== false) {
                $i++;
                $ebayID[] = $divElement->getAttribute('id');
                $ebayPrice[$i] = 0;
                // echo $divElement->getAttribute('id');
                // echo "<br>";

                foreach ($divElement->getElementsByTagName('a') as $link) {
                    if ($link->getAttribute('class') == "vip") {


                        $ebayProducts[] = $link->nodeValue;
                        $ebayLinks[] = $link->getAttribute('href');
                    }
                }
                foreach ($divElement->getElementsByTagName('li') as $link) {

                    if ($link->getAttribute('class') == "lvprice prc") {
                        foreach ($divElement->getElementsByTagName('span') as $link1) {

                            if ($link1->getAttribute('class') == "bold") {
                                $originalPrice = $link1->nodeValue;
                                $Price = str_replace("$", "", $originalPrice);
                                $Price = str_replace(",", "", $Price);
                                $Price = floatval($Price);
                                $ebayPrice[$i] = $Price;
                                break;
                            }
                        }
                    }
                }

            }
        }
    }

    // echo "<br />";
    // echo count($ebayLinks);
    // echo "<br />";
    // echo count($ebayProducts);
    // echo "<br />";
    // echo count($ebayID);
    // echo "<br />";
    // echo count($ebayPrice);
    // echo "<br />";

    for ($i = 0; $i < count($ebayLinks); $i++) {
        $sql = "INSERT IGNORE INTO Search_Info (searchKey, url,ProductName,ProductID,ProductPrice,StoreName) VALUES ('$searchItem','$ebayLinks[$i]','$ebayProducts[$i]','$ebayID[$i]','$ebayPrice[$i]','Ebay')";
        if ($conn->query($sql) === TRUE) {
            //  echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // End Ebay


    $sql = "SELECT searchKey, url,ProductName,ProductID,ProductPrice,StoreName FROM Search_Info WHERE searchKey = '$searchItem' ORDER BY ProductPrice DESC";
    $result = $conn->query($sql);

    $i = 0;
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $i++;
            echo "<tr>";
            echo "<td>$i</td>";
            echo "<td><a href='" . $row["url"] . "' target='_blank'>" . $row["ProductName"] . "</a></td>";
            $price = $row["ProductPrice"];
            if ($price != 0) {
                echo "<td>" . $row["ProductPrice"] . "</td>";
            } else {
                echo "<td>N/A</td>";
            }
            echo "<td>" . $row["StoreName"] . "</td>";

            echo "</tr>";
        }
    } else {
        echo "0 results";
    }


    ?>
    </tbody>
</table>
</body>
</html>