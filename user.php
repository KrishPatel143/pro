<?php

include("connection.php");
include("functions.php");

session_start();
$userId = $_SESSION['user_id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $deviceName = $_POST['deviceName'];
    $deviceDescription = $_POST['deviceDescription'];
    $selectedEvents = $_POST['event']; // This will be an array of selected event IDs

    if ($userId !== null) {
        // Start transaction
        $con->begin_transaction();

        // Insert the device into the devices table
        $stmt = $con->prepare("INSERT INTO devices (name, description, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $deviceName, $deviceDescription, $userId);
        
        if ($stmt->execute()) {
            // Get the ID of the newly inserted device
            $newDeviceId = $stmt->insert_id;
            $stmt->close();

            // Now, insert the device-event associations
            $stmt = $con->prepare("INSERT INTO event_device (event_id, device_id) VALUES (?, ?)");
            
            foreach ($selectedEvents as $eventId) {
                $stmt->bind_param("ii", $eventId, $newDeviceId);
                if (!$stmt->execute()) {
                    echo "Error: " . $stmt->error;
                    // If there is an error, roll back the transaction
                    $con->rollback();
                    exit();
                }
            }

            // If everything is fine, commit the transaction
            $con->commit();
            $stmt->close();

            exit();
        } else {
            echo "Error: " . $stmt->error;
            $stmt->close();
        }
    } else {
        echo "User ID is not set. Please login again.";
    }
}




// Let's assume you have a specific device ID to look up
$specificDeviceId = 5; // replace with the actual device ID you want to query

$query = "SELECT e.* FROM events AS e
          JOIN event_device AS de ON e.id = de.event_id
          WHERE de.device_id = ? AND e.status != 'unpublished'";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $specificDeviceId);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $eventsList = array();
    while ($row = $result->fetch_assoc()) {
        $eventsList[] = $row; // Add each event to the array
    }
    $stmt->close();

    // Now $eventsList contains all the events associated with the specific device
    // You can use $eventsList to display the events or process them as needed
    foreach ($eventsList as $event) {
        echo "Event ID: " . $event['id'] . " - Title: " . $event['title'] . "<br>";
    }

} else {
    echo "Error: " . $stmt->error;
}



    $query = "SELECT * FROM events";
    $result = mysqli_query($con, $query);

    $publishedEvents = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['status'] != "unpublished") {
                $publishedEvents[] = $row;
            }
        }
    } else {
        echo "<p>Error fetching events: " . mysqli_error($con) . "</p>";
    }




    $query = "SELECT * FROM devices WHERE user_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $devices = array();
    while ($row = $result->fetch_assoc()) {
        $devices[] = $row; // Add each device to the array
    }
    $stmt->close();
    // Now you have an array of devices in $devices
} else {
    echo "Error fetching devices: " . $stmt->error;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Event Hub </title>
    <style>

    </style>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="user.css">


</head>

<body>

    <nav class="user-dashboard-nav">
        <ul>
            <li><a href="#edit-profile">Edit My Profile</a></li>
            <li><a href="#my-devices">My Devices</a></li>
            <li><a href="#register-event">Register in Event</a></li>
        </ul>
        <div class="avatar-container"><a href="#user-profile"><img src="images/about-img.jpg" alt="User Avatar"></a>
        </div>
    </nav>

    <!-- Profile Photo Modal -->
    <div id="profilePhotoModal" class="modal">
        <div class="modal-content">
            <img src="images/about-img.jpg" alt="User Avatar" class="modal-avatar">
            <button id="deletePhotoBtn" class="delete-btn">Delete Photo</button>
        </div>
    </div>

    <section class="events-section">
        <section class="home-section">
            <div class="container">
                <h1>Welcome Dear User</h1>
            </div>
        </section>



        <div>
            <div class="profile-section">
                <div class="profile-display-card">
                    <img src="images/about-img.jpg" alt="User Avatar" class="profile-avatar">
                    <div class="profile-details">
                        <h3>John Doe</h3>
                        <p>Email: johndoe@example.com</p>
                    </div>
                </div>
                <div class="edit-icon" onclick="toggleEditForm()">✎</div>
            </div>

            <!-- Hidden Edit Form -->
            <div class="edit-form-container" id="editForm" style="display:none;">
                <input type="text" placeholder="Your Name" id="userName">
                <input type="email" placeholder="Your Email" id="userEmail">
                <div class="password-field">
                    <input type="password" placeholder="Password" id="userPassword">
                    <button type="button" onclick="togglePasswordVisibility()">Show</button>
                </div>
                <button type="button" onclick="updateProfile()">Submit</button>
                <button type="button" onclick="toggleEditForm()">Cancel</button>
            </div>

          
            <!-- Hidden Form for Adding/Editing Device -->
            <div class="device-form-container" id="deviceFormContainer" style="display:none;">
                <input type="text" id="deviceName" placeholder="Device Name">
                <textarea id="deviceDescription" placeholder="Device Description"></textarea>
                <button type="button" onclick="submitDeviceForm()">Submit</button>
                <button type="button" onclick="toggleDeviceForm()">Cancel</button>
            </div>





            <div class="devices-container" id="devices-container">
    <?php foreach ($devices as $device): ?>
        <div class="device-card">
            <h3><?= htmlspecialchars($device['name']) ?></h3>
            <p class="device-description"><?= htmlspecialchars($device['description']) ?></p>
            <div class="device-actions">
                <button class="edit-btn" >Edit</button>
                 <form method="post" action="" class="inline-form">
                <input type="hidden" name="delete_device_id" value="<?= $device['id'] ?>">
                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this device?');">Delete</button>
            </form>
            </div>
        </div>
    <?php endforeach; ?>

                <!-- Add New Device Card -->
                <div class="device-card add-device" onclick="showDeviceForm()">+</div>
            </div>

         
            <div class="device-form-container" id="deviceForm">
                <form class="device-form" action="" method="post">
                    <label for="deviceName">Device Name:</label>
                    <input type="text" id="deviceName" name="deviceName">

                    <label for="deviceDescription">Device Description:</label>
                    <textarea id="deviceDescription" name="deviceDescription"></textarea>
                    <label for="deviceType">Event:</label>
                    <select id="event" name="event[]" multiple>
                    <?php foreach ($publishedEvents as $event): ?>
                        <option value="<?= htmlspecialchars($event['id']) ?>"><?= htmlspecialchars($event['title']) ?></option>
                    <?php endforeach; ?>
                    </select>

                    <div class="form-actions">
                        <button type="submit" name="submit">Submit</button>
                        <button type="button" onclick="hideDeviceForm()">Cancel</button>
                    </div>
                </form>
            </div>


            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const avatar = document.querySelector('.avatar-container img');
                    const modal = document.getElementById('profilePhotoModal');
                    const deleteBtn = document.getElementById('deletePhotoBtn');

                    // Show the modal when the avatar is clicked
                    avatar.addEventListener('click', () => {
                        modal.style.display = 'block';
                    });

                    // Close the modal on delete (or perform actual delete action)
                    deleteBtn.addEventListener('click', () => {
                        modal.style.display = 'none';
                        alert('Photo would be deleted!');
                        // Implement actual deletion logic here
                    });

                    // Click anywhere outside of the modal content to close it
                    window.onclick = (event) => {
                        if (event.target == modal) {
                            modal.style.display = 'none';
                        }
                    };
                });




                function toggleEditForm() {
                    const form = document.getElementById('editForm');
                    form.style.display = form.style.display === 'none' ? 'block' : 'none';
                }

                function togglePasswordVisibility() {
                    const passwordInput = document.getElementById('userPassword');
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                }

                // Placeholder for updateProfile function
                function updateProfile() {
                    // Here, you'd collect form values and update the profile display card accordingly
                    // This part requires integration with your backend to truly update user data
                    alert('Profile updated! (Demo)');
                }







                function toggleAddDeviceForm() {
                    const formContainer = document.getElementById('deviceFormContainer');
                    formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
            
                
                    // Placeholder for form submission logic
                }




                // Assuming a similar devices array and rendering logic as before

                function showDeviceForm(deviceId = null) {
                    if (deviceId) {
                        // Pre-fill form for editing existing device
                        // This part would need actual device data fetching logic
                        console.log(`Editing device ${deviceId}`);
                    } else {
                        // Clear the form for adding a new device
                        document.getElementById('deviceName').value = '';
                        document.getElementById('deviceDescription').value = '';
                        document.getElementById('deviceVisible').checked = false;
                    }
                    document.getElementById('deviceForm').style.display = 'block';
                }

                function hideDeviceForm() {
                    document.getElementById('deviceForm').style.display = 'none';
                }


                function editDevice(deviceId) {
                    // Show device form pre-filled with device info (simulated here)
                    showDeviceForm(deviceId);
                }



            </script>
</body>

</html>