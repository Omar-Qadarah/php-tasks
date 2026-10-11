<?php
require 'Registration.php';
  $obj = '';
  $errors=[];
  $name= "";
  $email= "";
  $mobile= "";
  $governorate= "";
  $submeted_track= "";
  $skills= [];
  $agree= "";
  $message= "";
  $allowedGovernorate = ['amman', 'irbid', 'aqaba', 'zarqa'];
  $allowedTrack = ['full stack', 'frontend', 'backend'];
  $availableSkills=['HTML','CSS','JavaScript'];


  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST["name"] ?? '') ;
    $email = trim($_POST['email']?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $governorate = trim($_POST['governorate']?? '');
    $submeted_track = trim($_POST['track']?? '');
    $skills =$_POST['skills']??[];
    $agree = isset($_POST['agree']);
    $message = trim($_POST['message']??'');
    if($name == ''){
      $errors['name'] = 'This field is required';
    }
    if($email == ''){
      $errors['email'] = 'This field is required';
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors['email'] = 'Invalid email format';
    }
    if($mobile == ''){
      $errors['mobile'] = 'This field is required';
    }elseif(!preg_match('/^07[789][0-9]{7}$/',$mobile)){
      $errors['mobile'] = 'Invalid mobile format ex: 0791234567';
    }
    if(!in_array($governorate,$allowedGovernorate,true)){
      $errors['governorate'] = 'Select governorate';
    }
    if(!in_array($submeted_track,$allowedTrack,true)){
      $errors['track'] = 'Select track';
    }
    if(!is_array($skills) && array_diff($skills, $availableSkills)){
      $skills = [];
    }    
    if(empty($skills)){
      $errors['skills'] = 'Select at least one skill';
    }
    if(!$agree){
      $errors['agree'] = 'You must accept terms and conditions';
    }    
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
   <form action="" method="post" novalidate>

  <label for="name">Full Name</label>
  <input type="text" id="name" name="name" value="<?=htmlspecialchars($name)?>">
  <?php if(isset($errors['name'])):?>
    <span class="error"> <?= htmlspecialchars($errors['name'])?></span>
    <?php endif; ?>
  <label for="email">Email</label>
  <input type="email" id="email" name="email" value="<?=htmlspecialchars($email)?>">
    <?php if(isset($errors['email'])): ?>
      <span class="error"> <?= htmlspecialchars($errors['email'])?></span>
    <?php endif; ?>

  <label for="mobile">Mobile</label>
  <input type="tel" id="mobile" name="mobile" placeholder="0791234567" value="<?= htmlspecialchars($mobile)?>">
      <?php if(isset($errors['mobile'])):  ?>
        <span class="error"> <?= htmlspecialchars($errors['mobile'])?></span>
      <?php endif; ?>
  <label for="governorate">Governorate</label>
  <select id="governorate" name="governorate">
    <option value="">Choose a governorate</option>
    <option value="amman" <?= $governorate === 'amman' ? 'selected' : '' ?>>Amman</option>
    <option value="irbid" <?= $governorate === 'irbid' ? 'selected' : '' ?>>Irbid</option>
    <option value="aqaba" <?= $governorate === 'aqaba' ? 'selected' : '' ?>>Aqaba</option>
    <option value="zarqa" <?= $governorate === 'zarqa' ? 'selected' : '' ?>>Zarqa</option>
  </select>
  <?php if(isset($errors['governorate'])): ?>
    <span class="error"> <?= htmlspecialchars($errors['governorate'])?></span> 
  <?php endif; ?>

  <label>Track</label>

  <label class="inline">
    <input type="radio" name="track" value="full stack" <?= $submeted_track === 'full stack' ? 'checked' : '' ?>>
    Full Stack
  </label>

  <label class="inline">
    <input type="radio" name="track" value="frontend" <?= $submeted_track === 'frontend' ? 'checked' : '' ?>>
    Frontend
  </label>

  <label class="inline">
    <input type="radio" name="track" value="backend" <?= $submeted_track === 'backend' ? 'checked' : '' ?>>
    Backend
  </label>
  <?php if(isset($errors['track'])): ?>
    <span class="error"> <?= htmlspecialchars($errors['track'])?></span>
  <?php endif; ?>
  <label>Skills you already have</label>

  <label class="inline">
    <input type="checkbox" name="skills[]" value="HTML" <?= in_array('HTML',$skills) ? 'checked' : ''?>>
    HTML
  </label>

  <label class="inline">
    <input type="checkbox" name="skills[]" value="CSS" <?= in_array('CSS',$skills) ? 'checked' : ''?>>
    CSS
  </label>

  <label class="inline">
    <input type="checkbox" name="skills[]" value="JavaScript" <?= in_array('JavaScript',$skills) ? 'checked' : ''?>>
    JavaScript
  </label>
    <?php if(isset($errors['skills'])): ?>
    <span class="error"> <?= htmlspecialchars($errors['skills'])?></span> 
  <?php endif; ?>

  <label for="message">Why do you want to join? (optional)</label>
  <textarea id="message" name="message" rows="4"><?= htmlspecialchars($message)?></textarea>

  <label class="inline terms">
    <input type="checkbox" name="agree" <?= $agree ? 'checked' : '' ?>>
    I agree to the academy terms
  </label>
  <?php if(isset($errors['agree'])): ?>
    <span class="error"> <?= htmlspecialchars($errors['agree'])?></span>
  <?php endif; ?>  
  <button type="submit">Register</button>
</form> 
  <?php   
  if($_SERVER["REQUEST_METHOD"] == "POST"){ 
    if(empty($errors)){
      echo '<p class="success"> Thank you for your time. </p>';
      $obj = new Registration($name, $email, $mobile, $governorate,$submeted_track, $skills, $message);
    }
  }
  ?>
<section class="registered">
  <p class="data">Name : <?= htmlspecialchars($name)?></p>
  <p class="data">Email : <?= htmlspecialchars($email) ?></p>
  <p class="data">Mobile number : <?= htmlspecialchars($mobile) ?></p>
  <p class="data">Governorate : <?= htmlspecialchars($governorate) ?></p>
  <p class="data">Track : <?= htmlspecialchars($submeted_track) ?></p>
  <p class="data">Skills : <?= htmlspecialchars(implode(', ',$skills)) ?></p>
  <p class="data">Message : <?= htmlspecialchars($message) ?></p>
</section>
</body>
</html>