<!-- Created by Root (a.k.a. Martyn) -->
<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    // Redirect to the login page if the user is not logged in
    header("Location: ../pages/login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Cynosure | Home</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
</head>

<body id="page-top">
    <!-- Your navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#page-top">CYNOSURE</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#page-top" style="text-transform: uppercase;">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tasks" style="text-transform: uppercase;">Tasks</a></li>
                    <li class="nav-item"><a class="nav-link" href="#monitoring" style="text-transform: uppercase;">Monitoring</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php" style="text-transform: uppercase;"><?php echo $username; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="../php/logoutUser.php" style="text-transform: uppercase;">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="container px-4 px-lg-5 h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end">
                    <h1 class="text-white font-weight-bold text-uppercase">
                        <?php
                        date_default_timezone_set('Europe/Amsterdam'); // Set Amsterdam timezone

                        $currentTime = date('H:i:s');
                        $currentTimeHour = date('H');

                        if ($currentTimeHour >= 5 && $currentTimeHour < 12) {
                            $greeting = 'Good morning, ' . $username;
                        } elseif ($currentTimeHour >= 12 && $currentTimeHour < 18) {
                            $greeting = 'Good afternoon, ' . $username;
                        } else {
                            $greeting = 'Good evening, ' . $username;
                        }

                        echo $greeting;
                        ?>
                    </h1>
                    <hr class="divider" />
                </div>
                <div class="col-lg-8 align-self-baseline">
                    <a class="btn btn-primary btn-xl" href="#tasks">Your tasks</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Turnkey -->
    <section class="page-section bg-primary" id="tasks">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="text-white mt-0">Turnkey</h2>
                    <hr class="divider divider-light" />
                    <p class="text-white-75 mb-4">"You take the blue pill and you will create a new infrastructure, You take the red pill and you destroy your current digital world."</p>

                    <!-- New row for buttons -->
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn btn-light btn-xl" href="#comingsoon">Create infrastructure</a>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-light btn-xl" href="#comingsoon">Destroy infrastructure</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services-->
    <section class="page-section" id="monitoring">
        <div class="container px-4 px-lg-5">
            <h2 class="text-center mt-0">Monitoring</h2>
            <hr class="divider" />
            <div class="row gx-4 gx-lg-5">
                <?php
                require 'json/vendor/autoload.php'; // Include the AWS SDK for PHP

                use Aws\Ec2\Ec2Client;

                $sharedConfig = [
                    'region'      => 'your-region',         // Replace with your AWS region
                    'version'     => 'latest',
                    'credentials' => [
                        'key'    => 'your-access-key',      // Replace with your AWS access key
                        'secret' => 'your-secret-key',      // Replace with your AWS secret key
                    ],
                ];

                // Create an EC2 client
                $ec2Client = new Ec2Client($sharedConfig);

                // Specify the instance ID
                $instanceId = 'i-068d54cd1836731d0';

                try {
                    // Describe the instance
                    $result = $ec2Client->describeInstances([
                        'InstanceIds' => [$instanceId],
                    ]);

                    // Check if the instance exists
                    $reservations = $result['Reservations'];
                    if (count($reservations) > 0) {
                        $instance = $reservations[0]['Instances'][0];

                        // Determine the color based on instance state
                        $stateColor = ($instance['State']['Name'] === 'running') ? 'limegreen' : 'red';

                        echo '<div class="col-lg-3 col-md-6 text-center">';
                        echo '<div class="mt-5">';
                        echo '<div class="mb-2"><i class="bi-hdd fs-1 text-primary"></i></div>';
                        echo "<h3 class='h4 mb-2'>EC2 Instance {$instance['InstanceId']}</h3>";
                        echo "<p class='text-muted mb-0'>State: <strong style='color: {$stateColor};'>{$instance['State']['Name']}</strong></p>";
                        echo "<p class='text-muted mb-0'>Public IP: <strong>{$instance['PublicIpAddress']}</strong></p>";
                        echo "<p class='text-muted mb-0'>Private IP: <strong>{$instance['PrivateIpAddress']}</strong></p>";
                        echo "<p class='text-muted mb-0'>Instance Type: <strong>{$instance['InstanceType']}</strong></p>";
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo "Instance with ID {$instanceId} not found.\n";
                    }
                } catch (Exception $e) {
                    echo "Error: {$e->getMessage()}\n";
                }

                ?>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance i32a</h3>
                        <p class="text-muted mb-0">Status: <span style="color: limegreen;"><strong>running</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance i12x</h3>
                        <p class="text-muted mb-0">Status: <span style="color: limegreen;"><strong>running</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd-fill fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance d81a</h3>
                        <p class="text-muted mb-0">Status: <span style="color: red;"><strong>offline</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance i27e</h3>
                        <p class="text-muted mb-0">Status: <span style="color: limegreen;"><strong>running</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance i11v</h3>
                        <p class="text-muted mb-0">Status: <span style="color: limegreen;"><strong>running</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance i12x</h3>
                        <p class="text-muted mb-0">Status: <span style="color: limegreen;"><strong>running</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance x00a</h3>
                        <p class="text-muted mb-0">Status: <span style="color: limegreen;"><strong>running</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance b00a</h3>
                        <p class="text-muted mb-0">Status: <span style="color: limegreen;"><strong>running</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd-fill fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance l33t</h3>
                        <p class="text-muted mb-0">Status: <span style="color: red;"><strong>offline</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance i12x</h3>
                        <p class="text-muted mb-0">Status: <span style="color: limegreen;"><strong>running</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-hdd fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">EC2 Instance i27e</h3>
                        <p class="text-muted mb-0">Status: <span style="color: limegreen;"><strong>running</strong></span></p>
                        <p class='text-muted mb-0'>Public IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Private IP: <strong></strong></p>
                        <p class='text-muted mb-0'>Instance Type: <strong></strong></p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer-->
    <footer class="bg-dark py-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">Copyright &copy; 2023 - Cynosure | Made by <strong>Root (Martyn)</strong></div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>
