<?php
    include ("connection.php");
    $currentDate = date('Y-m-d');

    $query = "SELECT * FROM events";
    $result = mysqli_query($con, $query);
    
    $upcomingEvents = [];
    $pastEvents = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Assuming the event date is stored in 'date' column in 'YYYY-MM-DD' format
            if ($row['date'] >= $currentDate) {
                // Event is upcoming
                $upcomingEvents[] = $row;
            } else {
                // Event is in the past
                $pastEvents[] = $row;
            }
        }
    } else {
        echo "<p>Error fetching events: " . mysqli_error($con) . "</p>";
    }

    mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Responsive Navbar</title>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <img src="images/hackathon-logo.png" alt="Your Logo">
        </div>
        <ul class="nav-links">
            <li><a href="#home-section">Home</a></li>
            <li><a href="#events-section">Event</a></li>
            <li><a href="#past-events-section">Past Event</a></li>
            <li><a href="#about-us-section">About Us</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>


    <!-- Existing nav and sidebar markup -->

    <section class="home-section" id="home-section">
        <div class="container">
            <h1>Welcome to Our Website</h1>
            <p>Join our community to connect and share!</p>
            <a href="login.php" class="join-btn">Join Us</a>
        </div>
    </section>




    <!-- Home section code above -->
    <section class="events-section" id="events-section">
        <div>
            <div class="container">
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="event-card">
                        <h3><?php echo $event['title']; ?></h3>
                        <img src="<?php echo $event['url']; ?>" alt="<?php echo $event['title']; ?> Image" class="event-image">
                        <p class="event-time">Date & Time: <?php echo $event['date']; ?></p>
                        <p class="event-venue">Venue: <?php echo $event['location']; ?></p>
                        <p class="event-description"><?php echo $event['description']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>



    <!-- Events section code above -->

    <section class="past-events-section" id="past-events-section">
        <div class="container">
            <!-- Example of a Past Event Card -->
            <?php foreach ($pastEvents as $event): ?>
                    <div class="event-card">
                        <h3><?php echo $event['title']; ?></h3>
                        <img src="<?php echo $event['url']; ?>" alt="<?php echo $event['title']; ?> Image" class="event-image">
                        <p class="event-time">Date & Time: <?php echo $event['date']; ?></p>
                        <p class="event-venue">Venue: <?php echo $event['location']; ?></p>
                        <p class="event-description"><?php echo $event['description']; ?></p>
                    </div>
                <?php endforeach; ?>

        </div>
    </section>

    <section class="about-us-section"id="about-us-section">
        <div class="container">
            <div class="team-photo">
                <img src="images/about-img.jpg" alt="Our Team">
            </div>
            <div class="about-content">
                <h3>About Our Website</h3>
                <p>Welcome to our website! Here, we aim to connect like-minded individuals and provide a platform for
                    sharing ideas and events. Join our community to stay updated and participate in upcoming events.</p>
                <a href="contact.html" class="contact-btn">Contact Us</a>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="container">
            <p>© 2024 Your Website Name. All rights reserved.</p>
            <a href="privacy-policy.html">Privacy Policy</a>
            <div class="social-icons">
                <a href="https://www.facebook.com/" target="_blank" aria-label="Facebook"><img src="facebook-icon.png" alt="Facebook"></a>
                <a href="https://www.twitter.com/" target="_blank" aria-label="Twitter"><img src="twitter-icon.png" alt="Twitter"></a>
                <a href="https://www.instagram.com/" target="_blank" aria-label="Instagram"><img src="instagram-icon.png" alt="Instagram"></a>
                <!-- Add more social icons as needed -->
            </div>
        </div>
    </footer>
    


</body>

</html>