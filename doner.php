<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form inputs
    $name = isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : "";
    $phone = isset($_POST["phone"]) ? htmlspecialchars($_POST["phone"]) : "";
    $email = isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : "";
    $bloodgroup = isset($_POST["blood-type"]) ? htmlspecialchars($_POST["blood-type"]) : "";
    $city = isset($_POST["city"]) ? htmlspecialchars($_POST["city"]) : "";
    $state = isset($_POST["state"]) ? htmlspecialchars($_POST["state"]) : "";

    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "bloodline_db";
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("INSERT INTO recipients (name,  phone, email, blood_type,  city, state) VALUES (?, ?, ?, ?, ?, ?)" );
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("ssssss", $name, $phone,$email, $bloodgroup, $city, $state);
    if ($stmt->execute()) {
        echo '<script>alert("Recipients Added successfully!");</script>';
    } else {
        echo '<script>alert("Error: Unable to register. Please try again later.");</script>';
    }


    $sql = "SELECT * FROM registered_users WHERE bloodgroup = '$bloodgroup'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    $donors = array();
    while($row = $result->fetch_assoc()) {
        $donors[] = $row;
    }
    session_start();
    $_SESSION['donors'] = $donors;

    header("Location: donorlist.php");
    exit();
    } else {
        echo "No matching donors found1.";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blood Line - connect the donors</title>

  <!-- favicon-->
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

  <!--css-->
  <link rel="stylesheet" href="./assets/css/style.css">
  

  <!-- google font link-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
  .popup {
      display: flex;
      align-items: center;
      justify-content: center;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 20px;
      background: linear-gradient(135deg, #ffffff, #a3d2ee);
      color: #0e254e;
      font-size: 16px;
      z-index: 9999;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }
    </style>
  <script>
    // Function to display the popup message
    function showPopup(message) {
      const popup = document.createElement("div");
      popup.className = "popup";
      popup.textContent = message;
      document.body.appendChild(popup);

      // Automatically close the popup after 3 seconds
      setTimeout(function () {
        popup.remove();
      }, 3000);
    }
  </script>
</head>

<body id="top">
  <!-- HEADER-->
  <header class="header">
    <div class="header-top">
      <div class="container">
        <ul class="contact-list">
          <li class="contact-item">
            <ion-icon name="mail-outline"></ion-icon>
            <a href="mailto:bloodlineBU@gmail.com" class="contact-link">bloodlineBU@gmail.com</a>
          </li>
          <li class="contact-item">
            <ion-icon name="call-outline"></ion-icon>
            <a href="tel:+917236853103" class="contact-link">+91-723-6853-103</a>
          </li>
        </ul>
        <ul class="social-list">
          <li>
            <a href="https://www.facebook.com" class="social-link">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>
          </li>
          <li>
            <a href="https://www.instagram.com" class="social-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>
          <li>
            <a href="https://twitter.com" class="social-link">
              <ion-icon name="logo-twitter"></ion-icon>
            </a>
          </li>
          <li>
            <a href="https://youtu.be" class="social-link">
              <ion-icon name="logo-youtube"></ion-icon>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="header-bottom" data-header>
      <div class="container">
        <a href="#" class="logo">Blood Line</a>
        <nav class="navbar container" data-navbar>
          <ul class="navbar-list">
            <li>
              <a href="index.php" class="navbar-link" data-nav-link>Home</a>
            </li>
            <li>
              <a href="doner.php" class="navbar-link" data-nav-link>Find donor</a>
            </li>
            <li>
              <a href="#about" class="navbar-link" data-nav-link>About Us</a>
            </li>
            <li>
              <a href="#blog" class="navbar-link" data-nav-link>Blog</a>
            </li>
            <li>
              <a href="contact.php" class="navbar-link" data-nav-link>Contact</a>
            </li>
          </ul>
        </nav>
        <a href="register.php" class="btn">Login / Register</a>
        <button class="nav-toggle-btn" aria-label="Toggle menu" data-nav-toggler>
          <ion-icon name="menu-sharp" aria-hidden="true" class="menu-icon"></ion-icon>
          <ion-icon name="close-sharp" aria-hidden="true" class="close-icon"></ion-icon>
        </button>
      </div>
    </div>
  </header>

  <main>
    <article>
     
      <!--SERVICE-->
<section class="section service" id="service" aria-label="service">
  <div class="container">
    <p class="section-subtitle text-center">Find the best Donor For You</p>
    <h2 class="h2 section-title text-center">FIND DONOR</h2>

    <form class="donor-form" action="#" method="POST">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
      </div>

      <div class="form-group">
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" required>
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="blood-type">Blood Type:</label>
        <select id="blood-type" name="blood-type">
          <option value="A+">A+</option>
          <option value="A-">A-</option>
          <option value="B+">B+</option>
          <option value="B-">B-</option>
          <option value="AB+">AB+</option>
          <option value="AB-">AB-</option>
          <option value="O+">O+</option>
          <option value="O-">O-</option>
        </select>
      </div>

      <div class="form-group">
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>
      </div>

      <div class="form-group">
        <label for="state">State:</label>
        <input type="text" id="state" name="state" required>
      </div>

      <button type="submit" class="btn">Find Donor</button>
    </form>
  </div>
</section>



      <!--ABOUT-->
      <section class="section about" id="about" aria-label="about">
        <div class="container">
          <figure class="about-banner">
            <img src="./assets/images/about-banner.png" width="470" height="538" loading="lazy" alt="about banner"
              class="w-100">
          </figure>
          <div class="about-content">
            <p class="section-subtitle">About Us</p>
            <h2 class="h2 section-title">We Care For Your Loved Ones ꨄ︎</h2>
            <p class="section-text section-text-1">
              At Blood Line, we are passionate about connecting blood donors with recipients and bridging the gap in the healthcare industry.
               Our mission is to provide a seamless and efficient experience for both donors and recipients, ensuring timely access to life-saving blood transfusions.
            </p>
            <p class="section-text">
              We strive to create a community that fosters empathy, support, and solidarity among individuals who are committed to making a difference.
               Whether you're a potential donor or someone in need of blood, we are here to assist you every step of the way.
            </p>
            <a href="about.html" class="btn">Read more About Us</a>
          </div>
        </div>
      </section>

      <!--services-->

      <section class="section doctor" aria-label="doctor">
        <div class="container">
          <p class="section-subtitle text-center">Emergency !</p>
          <h2 class="h2 section-title text-center">Our other services</h2>
          <ul class="has-scrollbar">
            <li class="scrollbar-item">
              <div class="doctor-card">
                <div class="card-banner img-holder" style="--width: 460; --height: 500;">
                  <img src="./assets/images/doctor-1.png" width="460" height="500" loading="lazy" alt="PREBOOK"
                    class="img-cover">
                </div>
                <h3 class="h3">
                  <a href="#" class="card-title">Pre Book Blood</a>
                </h3>
                <p class="card-subtitle">Book Blood For An upcoming Date</p>
                <ul class="card-social-list">
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-facebook"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-twitter"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="scrollbar-item">
              <div class="doctor-card">
                <div class="card-banner img-holder" style="--width: 460; --height: 500;">
                  <img src="./assets/images/doctor-2.png" width="460" height="500" loading="lazy" alt="AMBULANCE"
                    class="img-cover">
                </div>
                <h3 class="h3">
                  <a href="#" class="card-title">Call Ambulance</a>
                </h3>
                <p class="card-subtitle">Get our ambulance service</p>
                <ul class="card-social-list">
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-facebook"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-twitter"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="scrollbar-item">
              <div class="doctor-card">

                <div class="card-banner img-holder" style="--width: 460; --height: 500;">
                  <img src="./assets/images/doctor-3.png" width="460" height="500" loading="lazy" alt="WHY DONATE ?"
                    class="img-cover">
                </div>
                <h3 class="h3">
                  <a href="#" class="card-title">Why Donate ?</a>
                </h3>
                <p class="card-subtitle">Why donate blood ?</p>
                <ul class="card-social-list">
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-facebook"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-twitter"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="scrollbar-item">
              <div class="doctor-card">
                <div class="card-banner img-holder" style="--width: 460; --height: 500;">
                  <img src="./assets/images/doctor-4.png" width="460" height="500" loading="lazy" alt="CAN YOU DONATE"
                    class="img-cover">
                </div>
                <h3 class="h3">
                  <a href="canyoudonate.html" class="card-title">Can You Donate ?</a>
                </h3>
                <p class="card-subtitle">Check that can you donate blood</p>
                <ul class="card-social-list">
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-facebook"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-twitter"></ion-icon>
                    </a>
                  </li>
                  <li>
                    <a href="#" class="card-social-link">
                      <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </section>


      <!--CTA-->
      <section class="section cta" aria-label="cta">
        <div class="container">
          <figure class="cta-banner">
            <img src="./assets/images/cta-banner.png" width="1056" height="1076" loading="lazy" alt="cta banner"
              class="w-100">
          </figure>
          <div class="cta-content">
            <p class="section-subtitle">Give Blood Directly To Us</p>
            <h2 class="h2 section-title">We Are open And Welcoming Donors</h2>
            <a href="#" class="btn">Book schedule</a>
          </div>
        </div>
      </section>

      <!--BLOG-->
      <section class="section blog" id="blog" aria-label="blog">
        <div class="container">
          <p class="section-subtitle text-center">Our Blog</p>
          <h2 class="h2 section-title text-center">Latest Blog & News</h2>
          <ul class="blog-list">
            <li>
              <div class="blog-card">
                <figure class="card-banner img-holder" style="--width: 1180; --height: 800;">
                  <img src="./assets/images/blog-1.jpg" width="1180" height="800" loading="lazy"
                    alt="Cras accumsan nulla nec lacus ultricies placerat." class="img-cover">
                  <div class="card-badge">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <time class="time" datetime="2022-03-24">24th April 2023</time>
                  </div>
                </figure>
                <div class="card-content">
                  <h3 class="h3">
                    <a href="#" class="card-title">Write whatever you want here. Blah Blah Blah</a>
                  </h3>
                  <p class="card-text">
                    When it spins, when it swirls When it whirls, when it twirls Two little beautiful girls Lookin' puzzled, in a daze I know it's confusing you
                  </p>
                  <a href="#" class="card-link">Read More</a>
                </div>
              </div>
            </li>
            <li>
              <div class="blog-card">
                <figure class="card-banner img-holder" style="--width: 1180; --height: 800;">
                  <img src="./assets/images/blog-2.jpg" width="1180" height="800" loading="lazy"
                    alt="Dras accumsan nulla nec lacus ultricies placerat." class="img-cover">
                  <div class="card-badge">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <time class="time" datetime="2022-03-24">24th April 2022</time>
                  </div>
                </figure>
                <div class="card-content">
                  <h3 class="h3">
                    <a href="#" class="card-title">Write whatever you want here. Foo foo foo</a>
                  </h3>
                  <p class="card-text">
                    Now hush little baby, don't you cry Everything's gonna be alright Stiffen that upper lip up little lady, I told ya Daddy's here to hold ya through the night I know mommy's not here right now
                  </p>
                  <a href="#" class="card-link">Read More</a>
                </div>
              </div>
            </li>
            <li>
              <div class="blog-card">
                <figure class="card-banner img-holder" style="--width: 1180; --height: 800;">
                  <img src="./assets/images/blog-3.jpg" width="1180" height="800" loading="lazy"
                    alt="Seas accumsan nulla nec lacus ultricies placerat." class="img-cover">
                  <div class="card-badge">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <time class="time" datetime="2022-03-24">24th April 2023</time>
                  </div>
                </figure>
                <div class="card-content">
                  <h3 class="h3">
                    <a href="#" class="card-title">Write whatever you want here. Wee wee wee</a>
                  </h3>
                  <p class="card-text">
                    Victim of the great machine, in love with everything I see Neon lights surrounding me, I indulge in luxury Everything I do is wrong
                  </p>
                  <a href="#" class="card-link">Read More</a>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </section>
    </article>
  </main>


  <!--FOOTER-->
  <footer class="footer">
    <div class="footer-top section">
      <div class="container">
        <div class="footer-brand">
          <a href="#" class="logo">Blood Line</a>
          <p class="footer-text">
            We are passionate about connecting blood donors with recipients and bridging the gap in the healthcare industry. 
            We strive to create a community that fosters empathy, support, and solidarity among individuals who are committed to making a difference.
          </p>
          <div class="schedule">
            <div class="schedule-icon">
              <ion-icon name="time-outline"></ion-icon>
            </div>
            <span class="span">
              24 X 7:<br>
              365 Days
            </span>
          </div>
        </div>
        <ul class="footer-list">
          <li>
            <p class="footer-list-title">Other Links</p>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">Home</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">Find donor</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">About us</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">Blog</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">Contact</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">Login / Register</span>
            </a>
          </li>
        </ul>
        <ul class="footer-list">
          <li>
            <p class="footer-list-title">Our Services</p>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
          <li>
            <a href="#" class="footer-link">
              <ion-icon name="add-outline"></ion-icon>
              <span class="span">xxxxxxxxx</span>
            </a>
          </li>
        </ul>
        <ul class="footer-list">
          <li>
            <p class="footer-list-title">Contact Us</p>
          </li>
          <li class="footer-item">
            <div class="item-icon">
              <ion-icon name="location-outline"></ion-icon>
            </div>
            <a href="https://maps.app.goo.gl/hM3SVbi9a9uLcBq38">
            <address class="item-text">
              Tech Zone 2 Near Pari Chowk,<br>
              Bennett University , Greater Noida, UP IN
            </address>
          </a>
          </li>
          <li class="footer-item">
            <div class="item-icon">
              <ion-icon name="call-outline"></ion-icon>
            </div>
            <a href="tel:+917236853103" class="footer-link">+91-7236-853-103</a>
          </li>
          <li class="footer-item">
            <div class="item-icon">
              <ion-icon name="mail-outline"></ion-icon>
            </div>
            <a href="mailto:help@example.com" class="footer-link">bloodlineBU@gmail.com</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p class="copyright">
          &copy; 2024 All Rights Reserved by Blood Line
        </p>
        <ul class="social-list">
          <li>
            <a href="https://www.facebook.com/andro.pool.54?mibextid=ZbWKwL" class="social-link">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>
          </li>
          <li>
            <a href="https://www.instagram.com/_vladimir_putin.___/" class="social-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>
          <li>
            <a href="https://twitter.com/Annabel07785340" class="social-link">
              <ion-icon name="logo-twitter"></ion-icon>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </footer>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the email address
    if (isset($_POST["email_address"]) && filter_var($_POST["email_address"], FILTER_VALIDATE_EMAIL)) {
        // Sanitize the email address to prevent SQL injection
        $email = htmlspecialchars($_POST["email_address"]);
        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bloodline_db";
        // Create a connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query to insert the email into the database
        $stmt = $conn->prepare("INSERT INTO response_back (email) VALUES (?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        // Check if the email is inserted successfully
        if ($stmt->affected_rows > 0) {
            // Email added successfully, show the popup message
            echo '<script>showPopup("Email added successfully!");</script>';
        } else {
            // Unable to add email, show the popup message
            echo '<script>showPopup("Error: Unable to add email. Please try again later.");</script>';
        }
        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        // Invalid email address, show the popup message
        echo '<script>showPopup("Error: Invalid email address. Please enter a valid email.");</script>';
    }
}
?>

  <!--BACK TO TOP-->
  <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
    <ion-icon name="caret-up" aria-hidden="true"></ion-icon>
  </a>

  <!--custom js link-->
  <script src="./assets/js/script.js" defer></script>
  <!--ionicon link-->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>
