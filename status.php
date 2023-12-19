<?php
require 'vendor/autoload.php'; // Include the AWS SDK for PHP

use Aws\Ec2\Ec2Client;

// Set up AWS region
$sharedConfig = [
    'region'  => 'your-region', // Replace with your AWS region
    'version' => 'latest',
];

// Create an EC2 client with credentials from the AWS CLI configuration
$ec2Client = new Ec2Client($sharedConfig);

// Specify the instance ID
$instanceId = 'i-068d54cd1836731d0';

try {
    // Describe the instance
    $result = $ec2Client->describeInstances([
        'InstanceIds' => [$instanceId],
    ]);

    // Extract instance information
    $instance = $result['Reservations'][0]['Instances'][0];

    // Output instance details
    echo "Instance ID: {$instance['InstanceId']}\n";
    echo "Instance State: {$instance['State']['Name']}\n";
    echo "Public DNS: {$instance['PublicDnsName']}\n";
    echo "Public IP: {$instance['PublicIpAddress']}\n";
    echo "Private IP: {$instance['PrivateIpAddress']}\n";

    // You can output more details as needed

} catch (Exception $e) {
    // Handle exceptions
    echo "Error: {$e->getMessage()}\n";
}
?>
