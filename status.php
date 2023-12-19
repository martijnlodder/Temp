               <?php

                require 'json/vendor/autoload.php';

                use Aws\Ec2\Ec2Client;

                $awsAccessKey = '';
                $awsSecretKey = '';
                $region = 'eu-central-1';

                $ec2Client = new Ec2Client([
                    'region'      => $region,
                    'credentials' => [
                        'key'    => $awsAccessKey,
                        'secret' => $awsSecretKey,
                    ],
                ]);

                $instanceId = 'i-068d54cd1836731d0';

                try {
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
