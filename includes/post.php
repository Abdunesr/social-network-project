
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php

echo '<div class="post">';
if($row['post_public'] == 'Y') {
    echo '<p class="public">';
    echo 'Public';
}else {
    echo '<p class="public">';
    echo 'Private';
}
echo '<br>';
echo '<span class="postedtime">' . $row['post_time'] . '</span>';
echo '</p>';
echo '<div>';
include 'profile_picture.php';
echo '<a class="profilelink" href="profile.php?id=' . $row['user_id'] .'">' . $row['user_firstname'] . ' ' . $row['user_lastname'] . '<a>';
echo'</div>';
echo '<br>';
echo '<p class="caption">' . $row['post_caption'] . '</p>';
echo '<center>'; 
$target = glob("data/images/posts/" . $row['post_id'] . ".*");
if($target) {
    echo '<img src="' . $target[0] . '" style="max-width:580px">'; 
    echo '<br><br>';
}
echo '</center>';
echo '<div class="like-comment">';
echo '<span class="like-icon" onclick="toggleLike()"><i class="far fa-heart"></i></span>';
echo '<span class="like-count">0 likes</span>';
echo '<input type="text" placeholder="Add a comment">';
echo '<button onclick="addComment()">Comment</button>';
echo '</div>';
echo '<div class="comments">';

echo '</div>';






?>



<script>
    // JavaScript for functionality
    let isLiked = false;
    let likeCount = 0;

    function toggleLike() {
      // Add logic to toggle liking/unliking a post
      if (isLiked) {
        likeCount--;
        document.querySelector('.like-count').textContent = likeCount + " likes";
        document.querySelector('.like-icon').innerHTML = '<i class="far fa-heart"></i>';
        document.querySelector('.like-icon').style.color = 'black';
      } else {
        likeCount++;
        document.querySelector('.like-count').textContent = likeCount + " likes";
        document.querySelector('.like-icon').innerHTML = '<i class="fas fa-heart"></i>';
        document.querySelector('.like-icon').style.color = 'red';
      }
      isLiked = !isLiked;
    }

    function addComment() {
      // Add logic to handle adding a comment
      var comment = document.querySelector('input[type="text"]').value;
      var commentsDiv = document.querySelector('.comments');
      var newComment = document.createElement('p');
      newComment.textContent = comment;
      commentsDiv.appendChild(newComment);
    }
    </script>

    
</body>
</html>