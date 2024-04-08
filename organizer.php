    <?php
    include("connection.php"); 

    $errorTag = "No Error";



    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'publish' && isset($_POST['eventID'])) {
            $eventID = mysqli_real_escape_string($con, $_POST['eventID']);
            $query = "UPDATE events SET status='published' WHERE id='$eventID'";
            mysqli_query($con, $query);
        } elseif ($action == 'delete' && isset($_POST['eventID'])) {
            $eventID = mysqli_real_escape_string($con, $_POST['eventID']);
            $query = "DELETE FROM events WHERE id='$eventID'";
            mysqli_query($con, $query);
        } elseif ($action == 'deleteUser' && isset($_POST['userId'])) {
            $userId = mysqli_real_escape_string($con, $_POST['userId']);
            $query = "DELETE FROM users WHERE id='$userId'";
            mysqli_query($con, $query);
        }elseif ($action == 'makeOrganizer' && isset($_POST['userId'])) {
            $userId = mysqli_real_escape_string($con, $_POST['userId']);
            $query = "UPDATE users SET role='organizer' WHERE id='$userId'";
            mysqli_query($con, $query);
        } 
    
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    if ( isset($_POST['submit'])) {
        $eventName = mysqli_real_escape_string($con, $_POST['eventName']);
        $eventTime = mysqli_real_escape_string($con, $_POST['eventTime']);
        $eventLocation = mysqli_real_escape_string($con, $_POST['eventLocation']);
        $eventDescription = mysqli_real_escape_string($con, $_POST['eventDescription']);
        $eventImage = mysqli_real_escape_string($con, $_POST['imageurl']);
        $query = "INSERT INTO events (title, date, location, description, url) VALUES ('$eventName', '$eventTime', '$eventLocation', '$eventDescription', '$eventImage')";
        
        if (mysqli_query($con, $query)) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $errorTag = "ERROR: Could not execute the query.";
        }
    }
    






    $query = "SELECT * FROM events";
    $result = mysqli_query($con, $query);
    
    $publishedEvents = [];
    $unpublishedEvents = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['status'] == "unpublished") {
                $unpublishedEvents[] = $row;
            } else {
                $publishedEvents[] = $row;
            }
        }
    } else {
        echo "<p>Error fetching events: " . mysqli_error($con) . "</p>";
    }





    $query = "SELECT * FROM users";
    $result = mysqli_query($con, $query);


    $users = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
            
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
        <link rel="stylesheet" href="organizer-styles.css">
        <title>Organizer Dashboard</title>
    </head>

    <body>
        <nav class="organizer-navbar">
            <ul class="nav-links">
                <li><a href="#events-container">Create Event</a></li>
                <li><a href="#members-section">View Participants</a></li>
                <li><a href="#unpublished-events-section">Manage Events</a></li>
                <li><a href="#members-section">Manage Participants</a></li>
            </ul>
        </nav>

        <!-- Content will go here -->
        <section class="home-section">
            <div class="container">
                <h1> Welcome Dear Organizer</h1>
            </div>
        </section>


        <section class="events-section">
            <div class="events-container" id="events-container">
        <?php foreach ($publishedEvents as $event): ?>
            <div class="event-card">
                <h3><?php echo $event['title']; ?></h3>
                <img src="<?php echo $event['url']; ?>" alt="<?php echo $event['title']; ?> Image" class="event-image">
                <p class="event-time">Date & Time: <?php echo $event['date']; ?></p>
                <p class="event-venue">Venue: <?php echo $event['location']; ?></p>
                <p class="event-description"><?php echo $event['description']; ?></p>
                <form method="POST">
    <input type="hidden" name="eventID" value="<?php echo $event['id']; ?>">
    <input type="hidden" name="action" value="delete">
    <button type="submit" class="delete-btn" >Delete</button>
</form>

            </div>
        <?php endforeach; ?>

        <div class="event-card add-event" onclick="showEventForm()">+</div>
    </div>


                <!-- Event Creation Form (Hidden Initially) -->
                <div class="event-form-container" id="eventForm">
                <form class="event-form" action="" method="post">

                <label for="eventName">Event Name:</label>
                        <input type="text" id="eventName" name="eventName">

                        <label for="eventTime">Event Time:</label>
                        <input type="text" id="eventTime" name="eventTime">

                        <label for="eventLocation">Event Location:</label>
                        <input type="text" id="eventLocation" name="eventLocation">

                        <label for="eventDescription">Event Description:</label>
                        <textarea id="eventDescription" name="eventDescription"></textarea>

                        <label for="eventDevice">Device:</label>
                        <select id="eventDevice" name="eventDevice">
                            <option value="">Select Device</option>
                            <!-- Device options will be populated here -->
                        </select>

                        <label for="eventImage">Event Image:</label>
                        <input type="text" id="imageurl" name="imageurl">
                        <!-- <input type="file" id="eventImage" name="eventImage"> -->

                        <div class="form-actions">
                        <button type="submit" name="submit">Submit</button>
                        <button type="button" onclick="hideEventForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>


        <section class="unpublished-events-section" id="unpublished-events-section">
            <h2>Unpublished Events</h2>

            <section class="past-events-section">
                <!-- Example of a Past Event Card -->

                <div class="events-list">
                    <!-- Example Event -->
                    <div class="event">
                    <?php foreach ($unpublishedEvents as $event): ?>
            <div class="event-card">
                <h3><?php echo $event['title']; ?></h3>
                <img src="<?php echo $event['url']; ?>" alt="<?php echo $event['title']; ?> Image" class="event-image">
                <p class="event-time">Date & Time: <?php echo $event['date']; ?></p>
                <p class="event-venue">Venue: <?php echo $event['location']; ?></p>
                <p class="event-description"><?php echo $event['description']; ?></p>
                <form method="POST">
    <input type="hidden" name="eventID" value="<?php echo $event['id']; ?>">
    <input type="hidden" name="action" value="publish">
    <button type="submit" class="publish-btn">Publish</button>
</form>

            </div>
        <?php endforeach; ?>
                        
                    </div>



                    <!-- Repeat for each unpublished event -->
                </div>

            </section>
        </section>

        <section class="members-section" id="members-section">
    <h2>Members List</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $user): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $user['user_name']; ?></td>
                    <td><?php echo $user['user_email']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display: inline-block; margin-right: 10px;">
                            <input type="hidden" name="action" value="makeOrganizer">
                            <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                            <button class="upgrade-btn">Organizer</button>
                        </form>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display: inline-block;">
                            <input type="hidden" name="action" value="deleteUser">
                            <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

        

        <!-- Link to your JavaScript file -->
        <script src="script.js"></script>
    </body>

    </html>