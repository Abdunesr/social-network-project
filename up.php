<!DOCTYPE html>
<html>
<head>
    <title>Social Network</title>
    <link rel="stylesheet" type="text/css" href="resources/css/main.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif; /* Custom font */
            background-color: #f0f0f0; /* Light background color */
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff; /* White background for container */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Box shadow for container */
            border-radius: 5px; /* Rounded corners for container */
        }
        .createpost {
            background-color: #f9f9f9; /* Light gray background for create post section */
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Box shadow */
        }
        .createpost textarea {
            width: calc(100% - 10px);
            margin-bottom: 10px;
            border: 1px solid #ccc; /* Border for textarea */
            border-radius: 5px; /* Rounded corners */
            padding: 10px;
        }
        .createpost label {
            cursor: pointer;
            display: block;
            margin-bottom: 10px;
        }
        .createpost label img {
            width: 50px;
            height: 50px;
            border-radius: 50%; /* Circular image */
            transition: transform 0.3s ease; /* Add transition for hover effect */
        }
        .createpost label img:hover {
            transform: scale(1.1); /* Enlarge image on hover */
        }
        .createpost input[type="submit"] {
            background-color: #4CAF50; /* Green submit button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px; /* Rounded corners */
            cursor: pointer;
            transition: background-color 0.3s ease; /* Add transition for hover effect */
        }
        .createpost input[type="submit"]:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        .post {
            background-color: #fff; /* White background for posts */
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Box shadow */
        }
        .post h2 {
            margin-top: 0;
        }
        .post p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'includes/navbar.php'; ?>
        <br>
        <div class="createpost">
            <form method="post" action="" onsubmit="return validatePost()" enctype="multipart/form-data">
                <h2>Make Post</h2>
                <hr>
                <span style="float:right; color:black">
                    <input type="checkbox" id="public" name="public">
                    <label for="public">Public</label>
                </span>
                Caption <span class="required" style="display:none;"> *You can't Leave the Caption Empty.</span><br>
                <textarea rows="6" name="caption" placeholder="Write your post here..."></textarea>
                <center><img src="" id="preview" style="max-width:580px; display:none;"></center>
                <div class="createpostbuttons">
                    <label>
                        <img src="images/photo.png" alt="Upload Image">
                        <input type="file" name="fileUpload" id="imagefile">
                    </label>
                    <input type="submit" value="Post" name="post">
                </div>
            </form>
        </div>
        <h1>News Feed</h1>
        <?php 
        // Public Posts Union Friends' Private Posts
        $sql = "SELECT posts.post_caption, posts.post_time, posts.post_public, users.user_firstname,
                        users.user_lastname, users.user_id, users.user_gender, posts.post_id
                FROM posts
                JOIN users
                ON posts.post_by = users.user_id
                WHERE posts.post_public = 'Y' OR users.user_id = {$_SESSION['user_id']}
                UNION
                SELECT posts.post_caption, posts.post_time, posts.post_public, users.user_firstname,
                        users.user_lastname, users.user_id, users.user_gender, posts.post_id
                FROM posts
                JOIN users
                ON posts.post_by = users.user_id
                JOIN (
                    SELECT friendship.user1_id AS user_id
                    FROM friendship
                    WHERE friendship.user2_id = {$_SESSION['user_id']} AND friendship.friendship_status = 1
                    UNION
                    SELECT friendship.user2_id AS user_id
                    FROM friendship
                    WHERE friendship.user1_id = {$_SESSION['user_id']} AND friendship.friendship_status = 1
                ) userfriends
                ON userfriends.user_id = posts.post_by
                WHERE posts.post_public = 'N'
                ORDER BY post_time DESC";
        $query = mysqli_query($conn, $sql);
        if(!$query){
            echo mysqli_error($conn);
        }
        if(mysqli_num_rows($query) == 0){
            echo '<div class="post">';
            echo 'There are no posts yet to show.';
            echo '</div>';
        }
        else{
            $width = '40px'; // Profile Image Dimensions
            $height = '40px';
            while($row = mysqli_fetch_assoc($query)){
                include 'includes/post.php';
                echo '<br>';
            }
        }
        ?>
        <br><br><br>
    </div>
    <script src="resources/js/jquery.js"></script>
    <script>
        // Invoke preview when an image file is chosen.
        $(document).ready(function(){
            $('#imagefile').change(function(){
                preview(this);
           
