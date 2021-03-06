<?php
  session_start();

  // echo $_SESSION['user_id'];

  if (!isset($_SESSION['email'])) {
    $_SESSION['msg'] = "You must log in first";
    $_SESSION['user_id'] = 'None';
  }
  if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = "None";
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['email']);
    $_SESSION['user_id'] = 'None';
    header("location: login.php");
  }
?>

<?php

    if (isset($_GET['user_id'])) {

        $user_id = $_GET['user_id'];

        $db = oci_pconnect("ecoeco", "qwerty123", "//localhost/xe");

        // Altynay sql query
        // Get user by user_id
        // $sql_query = "SELECT first_name, last_name, phone, city from users where user_id = '$user_id'";
        // or
        // $sql_query = "SELECT * from users where user_id = '$user_id'";

        $sql_query = "SELECT * from users where ID = '$user_id'"; // something

        $result = oci_parse($db, $sql_query);
        oci_execute($result);

        $row = oci_fetch_array($result, OCI_BOTH);

        //var_dump($row);

        $first_name = $row['FIRST_NAME'];
        $last_name = $row['SECOND_NAME'];
        //$address = $row['ADDRESS'];
        $contacts = $row['EMAIL'];
        //$city = $row['CITY'];
        $city = "city";

        // Altynai sql query
        // get all requests done by user, get count(*) for every organization that he requested
        // contact me
        // SELECT o.name, count(r.org_id) FROM REQUEST r join organizations o on r.org_id= o.org_id WHERE USER_ID LIKE '$user_id';
        // organization name, and count(*)

        $sql_query = "select first_name, id from users where id = '$user_id'";
        $list_organizations = oci_parse($db, $sql_query);
        oci_execute($list_organizations);

        // Altynai sql query
        // get count(*) questions, how many qustions he asked (total?)
        // SELECT COUNT(USER_ID) FROM QUESTIONS WHERE USER_ID LIKE '$user_id';

        $questions = "0";

    }

?>

<!DOCTYPE php>
<php>
<head>
  <title>FAQ</title>
  <link rel="stylesheet" type="text/css" href="style.css?ver=<?php echo rand(111,999)?>">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>
<body class="back" style="background-color: #d3f0e0">
    <nav>
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Главная</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="locations.php">Пункты</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Блог</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="faq.php">FAQ</a>
            </li>
            <!-- logged in user information -->
            <?php
            if (isset($_SESSION['email'])) {
                echo "<li class='nav-item'><a class='nav-link' href='profile.php?user_id=".$_SESSION['user_id']."'>".$_SESSION['email']."</a></li>";
                echo "<li class='nav-item'><a class='nav-link' href='index.php?logout='1'' style='color: red;'>logout</a></li>";
            }
            else{
                echo "<a class='nav-link' href='login.php'>Войти</a></li>";
            }
            ?>
        </ul>
    </nav>

    <div class="row no-gutters profile-block">
        <div class="photo-card">
            <div class="col-md-4">
                <!--Upload image-->
                <img src="images/4.jpg" class="card-img" alt="Profile image">
            </div>
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <!--Need data from table-->
                <h1 class="card-title"> Name <?php echo $first_name." ".$last_name;  ?></h1>
                <div class="sp_around">
                    <h4 class="card-text">Город: </h4>
                    <!--address--><h4><?php echo $city; ?></h4>
                </div>
                <div class="sp_around">
                    <h4 class="card-text">Контакты:</h4>
                    <!--contacts--><h4> <?php echo $contacts; ?> </h4>
                </div>
                <div class="sp_around">
                    <h4 class="card-text">Сдача материалов: </h4>
                </div>
                <div class="sp_around">
                    <!--Сколько материалов он сдал-->

                    <?php
                        echo "<table border='1'>\n";

                        while (($row = oci_fetch_array($list_organizations, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                            echo "<tr>\n";
                            foreach ($row as $item) {
                                echo "    <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</td>\n";
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";
                    ?>

                </div>
                <div class="sp_around">
                    <h4 class="card-text">Количество заданных вопросов: </h4>
                    <!--Инфо о скидках--><h4><?php echo $questions; ?></h4>
                </div>

                    <p style="display: flex; justify-content: center; margin-top: 3vh;" class="card-text"><small class="text-muted">Информация о пользователе</small></p>

            </div>
        </div>
    </div>


</body>
</html>
