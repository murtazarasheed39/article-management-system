<?php
require_once '../config/database.php';
require_once '../classes/User.php';

$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['userId']);

    $data = [
        'Full_Name'   => $_POST['Full_Name'],
        'email'       => $_POST['email'],
        'phone_Number'=> $_POST['phone_Number'],
        'User_Name'   => $_POST['User_Name'],
        'Password'    => !empty($_POST['Password']) ? password_hash($_POST['Password'], PASSWORD_DEFAULT) : '',
        'profile_Image' => '', // you can add upload later
        'Address'     => $_POST['Address'],
        'UserType'    => $_POST['UserType']
    ];

    $user->update($id, $data);

    header("Location: manage_authors.php");
    exit();
}
?>
